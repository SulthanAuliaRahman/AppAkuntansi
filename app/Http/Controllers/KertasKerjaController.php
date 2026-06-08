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
        // 1. AMBIL DATA DASAR ALUR NERACA SALDO (Rule 1)
        $accounts     = $this->service->getAccountsConfig();
        $transactions = $this->service->getTransactions();
        $ledgers      = $this->service->calculateLedgers($transactions);

        // 2. AMBIL DATA MUTASI DARI DATABASE AJP BARU (Rule 2)
        // Kita aggregate nominal DEBET dan KREDIT per akun perkiraan dari tabel detail_ajp
        $ajpMutations = DB::table('detail_ajp')
            ->select('akun_id', 
                DB::raw("SUM(CASE WHEN posisi = 'DEBET' THEN nominal ELSE 0 END) as total_debit"),
                DB::raw("SUM(CASE WHEN posisi = 'KREDIT' THEN nominal ELSE 0 END) as total_kredit")
            )
            ->groupBy('akun_id')
            ->get()
            ->keyBy('akun_id');

        // Mengambil master data akun dari database untuk mapping relasi ID ke Kode Akun
        $masterAkuns = Akuns::get()->keyBy('id');

        $rows = [];
        $sums = [
            'tbD' => 0, 'tbK' => 0, 'ajeD' => 0, 'ajeK' => 0,
            'nsdD' => 0, 'nsdK' => 0, 'lrD' => 0, 'lrK' => 0, 'nD' => 0, 'nK' => 0
        ];

        // 3. LOOPING & MENGURUTKAN BERDASARKAN NOMOR AKUN (Rule 3)
        // Kita gunakan $accounts sebagai acuan utama urutan kode akun
        foreach ($accounts as $code => $config) {
            
            // Ambil Saldo Awal dari Neraca Saldo persis seperti halaman Neraca Saldo
            $entries = $ledgers[$code] ?? [];
            $finalBalance = empty($entries) ? 0 : end($entries)['balance'];

            $isDebit = $config['normal'] === 'debit';
            $classCode = (int) ($config['classCode'] ?? 0); // Deteksi tipe akun (1=Aset, 4=Pendapatan, dll)

            // Inisialisasi awal kolom
            $tbD = $tbK = $ajeD = $ajeK = $nsdD = $nsdK = $lrD = $lrK = $nD = $nK = 0;

            // Isi Kolom 1 & 2: Neraca Saldo
            if ($isDebit) {
                $tbD = $finalBalance;
                $sums['tbD'] += $tbD;
            } else {
                $tbK = $finalBalance;
                $sums['tbK'] += $tbK;
            }

            // Cari apakah akun ini punya mutasi di database Jurnal Penyesuaian (AJP)
            // Kita cari baris master akun yang kode_akun-nya cocok dengan $code
            $matchedMaster = $masterAkuns->where('kode_akun', $code)->first();
            $ajpData = $matchedMaster ? ($ajpMutations[$matchedMaster->id] ?? null) : null;

            if ($ajpData) {
                $ajeD = (float) $ajpData->total_debit;
                $ajeK = (float) $ajpData->total_kredit;
                $sums['ajeD'] += $ajeD;
                $sums['ajeK'] += $ajeK;
            }

            // Isi Kolom 5 & 6: Neraca Saldo Setelah Penyesuaian (NSD)
            // Logika Akuntansi: Saldo Normal + Mutasi Sejenis - Mutasi Lawan
            if ($isDebit) {
                $nsdD = $tbD + $ajeD - $ajeK;
                $sums['nsdD'] += $nsdD;
            } else {
                $nsdK = $tbK + $ajeK - $ajeD;
                $sums['nsdK'] += $nsdK;
            }

            // Isi Kolom 7, 8, 9, 10: Klasifikasi Laba Rugi vs Neraca
            // Akun Kepala 4 (Pendapatan) & Kepala 5 (Beban) masuk ke LABA RUGI
            if ($classCode >= 4) {
                if ($isDebit) {
                    $lrD = $nsdD;
                    $sums['lrD'] += $lrD;
                } else {
                    $lrK = $nsdK;
                    $sums['lrK'] += $lrK;
                }
            } else {
                // Akun Kepala 1 (Aset), 2 (Liabilitas), & 3 (Ekuitas) masuk ke NERACA
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