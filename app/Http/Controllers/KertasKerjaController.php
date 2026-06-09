<?php

namespace App\Http\Controllers;

use App\Models\Ajp;
use App\Models\Akuns;
use App\Services\AkuntansiService;
use Illuminate\Support\Facades\DB;

class KertasKerjaController extends Controller
{
    public function __construct(private AkuntansiService $service) {}

    public function index()
    {
        // 1. AMBIL DATA DASAR ALUR NERACA SALDO
        $accounts     = $this->service->getAccountsConfig();
        $transactions = $this->service->getTransactions();
        $ledgers      = $this->service->calculateLedgers($transactions);

        // 2. AMBIL DATA MUTASI DARI DATABASE AJP BARU
        $ajpMutations = DB::table('detail_ajp')
            ->select('akun_id', 
                DB::raw("SUM(CASE WHEN posisi = 'DEBET' THEN nominal ELSE 0 END) as total_debit"),
                DB::raw("SUM(CASE WHEN posisi = 'KREDIT' THEN nominal ELSE 0 END) as total_kredit")
            )
            ->groupBy('akun_id')
            ->get()
            ->keyBy('akun_id');

        $masterAkuns = Akuns::get()->keyBy('id');

        $rows = [];
        $sums = [
            'tbD' => 0, 'tbK' => 0, 'ajeD' => 0, 'ajeK' => 0,
            'nsdD' => 0, 'nsdK' => 0, 'lrD' => 0, 'lrK' => 0, 'nD' => 0, 'nK' => 0
        ];

        // 3. LOOPING & MENGURUTKAN BERDASARKAN NOMOR AKUN
        foreach ($accounts as $code => $config) {
            
            // Ambil Saldo Awal dari Neraca Saldo
            $entries = $ledgers[$code] ?? [];
            $finalBalance = empty($entries) ? 0 : end($entries)['balance'];

            // Tarik data mutasi penyesuaian (jika ada)
            $matchedMaster = $masterAkuns->where('kode_akun', $code)->first();
            $ajpData = $matchedMaster ? ($ajpMutations[$matchedMaster->id] ?? null) : null;

            $ajeD = $ajpData ? (float) $ajpData->total_debit : 0;
            $ajeK = $ajpData ? (float) $ajpData->total_kredit : 0;

            // ATURAN BISNIS BARU: Jika di Neraca Saldo kosong DAN di AJP juga kosong, skip akun ini!
            if ($finalBalance == 0 && $ajeD == 0 && $ajeK == 0) {
                continue;
            }

            $isDebit = $config['normal'] === 'debit';
            $classCode = (int) ($config['classCode'] ?? 0);

            // Inisialisasi awal kolom baris tabel
            $tbD = $tbK = $nsdD = $nsdK = $lrD = $lrK = $nD = $nK = 0;

            // Isi Kolom 1 & 2: Neraca Saldo
            if ($isDebit) {
                $tbD = $finalBalance;
                $sums['tbD'] += $tbD;
            } else {
                $tbK = $finalBalance;
                $sums['tbK'] += $tbK;
            }

            // Akumulasikan subtotal AJP penyesuaian ke tfoot
            $sums['ajeD'] += $ajeD;
            $sums['ajeK'] += $ajeK;

            // Isi Kolom 5 & 6: Neraca Saldo Setelah Penyesuaian (NSD)
            if ($isDebit) {
                $nsdD = $tbD + $ajeD - $ajeK;
                $sums['nsdD'] += $nsdD;
            } else {
                $nsdK = $tbK + $ajeK - $ajeD;
                $sums['nsdK'] += $nsdK;
            }

            // Isi Kolom Laba Rugi vs Neraca
            if ($classCode >= 4) {
                if ($isDebit) {
                    $lrD = $nsdD;
                    $sums['lrD'] += $lrD;
                } else {
                    $lrK = $nsdK;
                    $sums['lrK'] += $lrK;
                }
            } else {
                if ($isDebit) {
                    $nD = $nsdD;
                    $sums['nD'] += $nD;
                } else {
                    $nK = $nsdK;
                    $sums['nK'] += $nK;
                }
            }

            $rows[] = compact('code', 'config', 'tbD', 'tbK', 'ajeD', 'ajeK', 'nsdD', 'nsdK', 'lrD', 'lrK', 'nD', 'nK');
        }

        // Perhitungan Selisih Laba Bersih / Rugi Bersih
        $labaRugiDiff = $sums['lrK'] - $sums['lrD'];
        $neracaDiff   = $sums['nD'] - $sums['nK'];
        $isProfit     = $labaRugiDiff >= 0;

        return view('akuntansi.kertas-kerja', compact('rows', 'sums', 'labaRugiDiff', 'neracaDiff', 'isProfit'));
    }
}