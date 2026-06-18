<?php

namespace App\Http\Controllers;

use App\Services\AkuntansiService;
use Illuminate\Http\Request;

class NeracaSaldoController extends Controller
{
    public function __construct(private AkuntansiService $service) {}

    public function index(Request $request)
    {
        // 1. Ambil data dasar konfigurasi akun dan mutasi buku besar
        $accounts     = $this->service->getAccountsConfig();
        $transactions = $this->service->getTransactions();
        $ledgers      = $this->service->calculateLedgers($transactions);

        // Filter akun berdasarkan akses user
        $user = auth()->user();
        $accessibleAkunCodes = $user->getAccessibleAkuns()->pluck('kode_akun')->toArray();

        $rows        = [];
        $totalDebit  = 0;
        $totalCredit = 0;

        // 2. Looping setiap akun untuk diambil "Final Balance" atau Saldo Akhirnya
        foreach ($accounts as $code => $config) {
            // Cek akses akun
            if (!$user->role->is_full_access && !in_array($code, $accessibleAkunCodes)) {
                continue;
            }

            $entries = $ledgers[$code] ?? [];

            // Ambil saldo akhir persis seperti cara Buku Besar mengambilnya
            $finalBalance = empty($entries) ? 0 : end($entries)['balance'];

            // ATURAN BISNIS BARU: Jika saldo akhirnya 0, skip/lewati akun ini (tidak dimasukkan ke tabel)
            if ($finalBalance == 0) {
                continue;
            }

            $debit  = 0;
            $credit = 0;

            // 3. Tentukan posisi kolom berdasarkan saldo normal akun tersebut
            if ($config['normal'] === 'debit') {
                $debit       = $finalBalance;
                $totalDebit += $finalBalance;
            } else {
                $credit      = $finalBalance;
                $totalCredit += $finalBalance;
            }

            // Masukkan ke array rows untuk dikirim ke template Blade
            $rows[] = compact('code', 'config', 'debit', 'credit');
        }

        // 4. Kirim ke view tanpa mengubah variabel aslinya (format warna & bentuk tetap aman)
        return view('akuntansi.neraca-saldo', compact('rows', 'totalDebit', 'totalCredit'));
    }
}