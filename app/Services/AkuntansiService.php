<?php

namespace App\Services;

use App\Models\Akuns;
use App\Models\EntriPenyesuaian;
use App\Models\Jurnal;
use App\Models\Pengaturan;
use App\Models\SaldoAwal;
use Illuminate\Support\Facades\DB;

class AkuntansiService
{
    public function getAccountsConfig(): array
    {
        return Akuns::with('jenisAkun')
            ->get()
            ->keyBy('kode_akun')
            ->map(fn($a) => [
                'name'       => $a->nama_akun,
                'type'       => 'general',
                'normal'     => strtolower($a->saldo_normal === 'DEBET' ? 'debit' : 'credit'),
                'class'      => $a->jenisAkun?->nama ?? 'Unknown',
                'classCode'  => $a->jenisAkun?->kode ?? '',
            ])
            ->toArray();
    }

    public function getInitialBalances(): array
    {
        return SaldoAwal::all()
            ->keyBy('kode_akun')
            ->map(fn($s) => [
                'debit'  => (int) $s->debet,
                'credit' => (int) $s->kredit,
            ])
            ->toArray();
    }

    public function getTransactions(): array
    {
       $driver = DB::connection()->getDriverName();

        // 2. Siapkan query dasar pengambilan data Jurnal Umum beserta detailnya
        $query = Jurnal::with('details');

        // 3. LOGIKA SMART OVEN: Sesuaikan cara urut tanggal berdasarkan merk database
        if ($driver === 'pgsql') {
            // Jalur aman tanpa crash untuk PostgreSQL ketat di laptop yanto
            $query->orderBy('tanggal', 'asc');
        } else {
            // Jalur asli bawaan proyek awal menggunakan MySQL/SQLite untuk teman-temanmu
            $query->orderByRaw('DATE(tanggal) ASC');
        }

        // 4. Terakhir, urutkan berdasarkan ID lalu ambil datanya
        $jurnals = $query->orderBy('id', 'asc')->get();

        return $jurnals->map(function ($jurnal) {
            $details = $jurnal->details;

            // Aggregate semua baris debet dan kredit — bukan hanya first()
            $totalDebet  = (int) $details->where('type', 'debet')->sum('jumlah');
            $totalKredit = (int) $details->where('type', 'kredit')->sum('jumlah');

            // Representasi akun utama untuk tampilan (akun pertama tiap sisi)
            $debitDetail  = $details->where('type', 'debet')->first();
            $kreditDetail = $details->where('type', 'kredit')->first();

            if (!$debitDetail || !$kreditDetail) {
                return null;
            }

            return [
                'id'           => $jurnal->id,
                'rawDate'      => $jurnal->tanggal,
                'date'         => $this->formatTanggal($jurnal->tanggal),
                'desc'         => $jurnal->keterangan,
                'is_static'    => $jurnal->is_static,
                // Akun representatif untuk kolom Ref di tabel
                'debitAcc'     => $debitDetail->akun_kode,
                'creditAcc'    => $kreditDetail->akun_kode,
                // Amount masing-masing sisi — bisa berbeda jika multi-entry
                'debitAmount'  => $totalDebet,
                'creditAmount' => $totalKredit,
                // Semua detail entries untuk keperluan ledger / buku besar
                'entries'      => $details->sortBy(fn($d) => $d->type === 'debet' ? 0 : 1)->map(fn($d) => [
                    'account' => $d->akun_kode,
                    'type'    => $d->type,
                    'amount'  => (int) $d->jumlah,
                ])->values()->toArray(),
            ];
        })->filter()->values()->toArray();
    }

    private function formatTanggal($tanggal): string
    {
        try {
            return \Carbon\Carbon::parse($tanggal)->translatedFormat('j F Y');
        } catch (\Exception $e) {
            return (string) $tanggal;
        }
    }

    public function isAdjustmentsEnabled(): bool
    {
        return Pengaturan::getValue('adjustments_enabled', '1') === '1';
    }

    public function calculateLedgers(array $transactions): array
    {
        $accounts        = $this->getAccountsConfig();
        $initialBalances = $this->getInitialBalances();
        $ledgers         = [];

        // Inisialisasi ledger dengan saldo awal tiap akun
        foreach ($accounts as $code => $config) {
            $init    = $initialBalances[$code] ?? ['debit' => 0, 'credit' => 0];
            $balance = $config['normal'] === 'debit'
                ? $init['debit'] - $init['credit']
                : $init['credit'] - $init['debit'];

            $ledgers[$code] = [[
                'date'    => '31 Mar',
                'desc'    => 'Saldo Awal (Neraca Saldo Maret)',
                'debit'   => $init['debit'],
                'credit'  => $init['credit'],
                'balance' => $balance,
            ]];
        }

        foreach ($transactions as $t) {
            // Format baru: tiap transaksi punya array 'entries' dengan per-akun detail
            if (!empty($t['entries'])) {
                foreach ($t['entries'] as $entry) {
                    $acc = $entry['account'];
                    if (!isset($ledgers[$acc])) continue;

                    $lastBal = end($ledgers[$acc])['balance'] ?? 0;
                    $isDebit = ($accounts[$acc]['normal'] ?? 'debit') === 'debit';
                    $amount  = (int) $entry['amount'];

                    if ($entry['type'] === 'debet') {
                        $ledgers[$acc][] = [
                            'date'    => $t['date'],
                            'desc'    => $t['desc'],
                            'debit'   => $amount,
                            'credit'  => 0,
                            'balance' => $lastBal + ($isDebit ? $amount : -$amount),
                        ];
                    } else {
                        $ledgers[$acc][] = [
                            'date'    => $t['date'],
                            'desc'    => $t['desc'],
                            'debit'   => 0,
                            'credit'  => $amount,
                            'balance' => $lastBal + ($isDebit ? -$amount : $amount),
                        ];
                    }
                }
                continue;
            }

            // Legacy format fallback (format lama tanpa 'entries')
            if (!isset($t['debitAcc'], $t['creditAcc'])) continue;

            $debitAmount  = (int) ($t['debitAmount']  ?? $t['amount'] ?? 0);
            $creditAmount = (int) ($t['creditAmount'] ?? $t['amount'] ?? 0);

            $dAcc = $t['debitAcc'];
            if (isset($ledgers[$dAcc])) {
                $lastBal = end($ledgers[$dAcc])['balance'] ?? 0;
                $isDebit = ($accounts[$dAcc]['normal'] ?? 'debit') === 'debit';
                $ledgers[$dAcc][] = [
                    'date'    => $t['date'],
                    'desc'    => $t['desc'],
                    'debit'   => $debitAmount,
                    'credit'  => 0,
                    'balance' => $lastBal + ($isDebit ? $debitAmount : -$debitAmount),
                ];
            }

            $cAcc = $t['creditAcc'];
            if (isset($ledgers[$cAcc])) {
                $lastBal = end($ledgers[$cAcc])['balance'] ?? 0;
                $isDebit = ($accounts[$cAcc]['normal'] ?? 'debit') === 'debit';
                $ledgers[$cAcc][] = [
                    'date'    => $t['date'],
                    'desc'    => $t['desc'],
                    'debit'   => 0,
                    'credit'  => $creditAmount,
                    'balance' => $lastBal + ($isDebit ? -$creditAmount : $creditAmount),
                ];
            }
        }

        return $ledgers;
    }

    public function getAdjustedBalances(array $transactions, bool $adjustmentsEnabled): array
    {
        $ledgers  = $this->calculateLedgers($transactions);
        $accounts = $this->getAccountsConfig();
        $adjusted = [];

        foreach ($ledgers as $code => $entries) {
            $latest          = end($entries);
            $adjusted[$code] = [
                'preAdjustment'   => $latest['balance'],
                'debitAdj'        => 0,
                'creditAdj'       => 0,
                'adjustedBalance' => $latest['balance'],
            ];
        }

        if ($adjustmentsEnabled) {
            EntriPenyesuaian::all()->each(function ($row) use (&$adjusted) {
                if (isset($adjusted[$row->kode_akun_debet])) {
                    $adjusted[$row->kode_akun_debet]['debitAdj'] += (int) $row->jumlah;
                }
                if (isset($adjusted[$row->kode_akun_kredit])) {
                    $adjusted[$row->kode_akun_kredit]['creditAdj'] += (int) $row->jumlah;
                }
            });
        }

        foreach ($adjusted as $code => $data) {
            $isDebit                            = ($accounts[$code]['normal'] ?? 'debit') === 'debit';
            $adjusted[$code]['adjustedBalance'] = $isDebit
                ? $data['preAdjustment'] + $data['debitAdj'] - $data['creditAdj']
                : $data['preAdjustment'] - $data['debitAdj'] + $data['creditAdj'];
        }

        return $adjusted;
    }
}