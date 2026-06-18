<?php

namespace App\Http\Controllers;

use App\Services\AkuntansiService;
use Dompdf\Dompdf;
use Dompdf\Options;
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

    public function exportPdf()
    {
        $data = $this->getExportData();
        $html = view('akuntansi.exports.neraca-saldo', $data)->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false);

        $pdf = new Dompdf($options);
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return response($pdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="neraca-saldo.pdf"',
        ]);
    }

    public function exportExcel()
    {
        $data = $this->getExportData();
        $html = view('akuntansi.exports.neraca-saldo', $data)->render();

        return response($html, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="neraca-saldo.xls"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    private function getExportData(): array
    {
        $accounts     = $this->service->getAccountsConfig();
        $transactions = $this->service->getTransactions();
        $ledgers      = $this->service->calculateLedgers($transactions);

        $user = auth()->user();
        $accessibleAkunCodes = $user->getAccessibleAkuns()->pluck('kode_akun')->toArray();

        $rows        = [];
        $totalDebit  = 0;
        $totalCredit = 0;

        foreach ($accounts as $code => $config) {
            if (!$user->role->is_full_access && !in_array($code, $accessibleAkunCodes)) {
                continue;
            }

            $entries = $ledgers[$code] ?? [];
            $finalBalance = empty($entries) ? 0 : end($entries)['balance'];

            if ($finalBalance == 0) {
                continue;
            }

            $debit  = 0;
            $credit = 0;

            if ($config['normal'] === 'debit') {
                $debit       = $finalBalance;
                $totalDebit += $finalBalance;
            } else {
                $credit      = $finalBalance;
                $totalCredit += $finalBalance;
            }

            $rows[] = compact('code', 'config', 'debit', 'credit');
        }

        return compact('rows', 'totalDebit', 'totalCredit');
    }
}