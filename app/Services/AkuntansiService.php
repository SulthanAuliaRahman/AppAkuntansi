<?php

namespace App\Services;

use App\Models\Akun;
use App\Models\EntriPenyesuaian;
use App\Models\Jurnal;
use App\Models\Pengaturan;
use App\Models\SaldoAwal;

class AkuntansiService
{
    public function getAccountsConfig(): array
    {
        return Akun::all()
            ->keyBy('kode')
            ->map(fn($a) => [
                'name'   => $a->nama,
                'type'   => $a->tipe,
                'normal' => $a->normal,
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
        return Jurnal::orderBy('id')
            ->get()
            ->map(fn($j) => [
                'id'        => $j->id,
                'date'      => $j->tanggal,
                'desc'      => $j->keterangan,
                'debitAcc'  => $j->akun_debet,
                'creditAcc' => $j->akun_kredit,
                'amount'    => (int) $j->jumlah,
                'is_static' => $j->is_static,
            ])
            ->toArray();
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
            // Sisi Debet
            $lastBal = end($ledgers[$t['debitAcc']])['balance'];
            $isDebit = $accounts[$t['debitAcc']]['normal'] === 'debit';
            $ledgers[$t['debitAcc']][] = [
                'date'    => $t['date'],
                'desc'    => $t['desc'],
                'debit'   => $t['amount'],
                'credit'  => 0,
                'balance' => $lastBal + ($isDebit ? $t['amount'] : -$t['amount']),
            ];

            // Sisi Kredit
            $lastBal2 = end($ledgers[$t['creditAcc']])['balance'];
            $isDebit2 = $accounts[$t['creditAcc']]['normal'] === 'debit';
            $ledgers[$t['creditAcc']][] = [
                'date'    => $t['date'],
                'desc'    => $t['desc'],
                'debit'   => 0,
                'credit'  => $t['amount'],
                'balance' => $lastBal2 + ($isDebit2 ? -$t['amount'] : $t['amount']),
            ];
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
                $adjusted[$row->kode_akun_debet]['debitAdj']   += (int) $row->jumlah;
                $adjusted[$row->kode_akun_kredit]['creditAdj'] += (int) $row->jumlah;
            });
        }

        foreach ($adjusted as $code => $data) {
            $isDebit                          = $accounts[$code]['normal'] === 'debit';
            $adjusted[$code]['adjustedBalance'] = $isDebit
                ? $data['preAdjustment'] + $data['debitAdj'] - $data['creditAdj']
                : $data['preAdjustment'] - $data['debitAdj'] + $data['creditAdj'];
        }

        return $adjusted;
    }
}
