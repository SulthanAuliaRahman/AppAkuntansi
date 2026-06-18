<?php

use App\Http\Controllers\BukuBesarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\JurnalPenutupController;
use App\Http\Controllers\KertasKerjaController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\NeracaSaldoController;
use App\Http\Controllers\PenyesuaianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaldoAwalController;
use App\Http\Controllers\admin\AdminUserController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\AksesAkunController;
use App\Http\Controllers\KategoriAkunController;
use Illuminate\Support\Facades\Route;

// PUBLIC ROUTES
Route::get('/', function () {
    return view('landingpage');
});

// AUTHENTICATED ROUTES (ALL USERS)
Route::middleware(['auth'])->group(function () {

    Route::post('/set-period', function (Illuminate\Http\Request $request) {
        $period = $request->input('period');
        $startDate = null;
        $endDate = now()->format('Y-m-d'); // Default end date is today
        $customMonth = null;
        $customYear = null;

        if ($period === '1_month') {
            $startDate = now()->startOfMonth()->format('Y-m-d');
            $endDate = now()->endOfMonth()->format('Y-m-d');
        } elseif ($period === '3_months') {
            $startDate = now()->subMonths(2)->startOfMonth()->format('Y-m-d');
            $endDate = now()->endOfMonth()->format('Y-m-d');
        } elseif ($period === '1_year') {
            $startDate = now()->startOfYear()->format('Y-m-d');
            $endDate = now()->endOfYear()->format('Y-m-d');
        } elseif ($period === 'custom_month') {
            $customMonth = $request->input('custom_month', now()->month);
            $customYear = $request->input('custom_year', now()->year);
            $date = \Carbon\Carbon::createFromDate($customYear, $customMonth, 1);
            $startDate = $date->startOfMonth()->format('Y-m-d');
            $endDate = $date->endOfMonth()->format('Y-m-d');
        }

        if (in_array($period, ['1_month', '3_months', '1_year', 'custom_month'])) {
            session([
                'global_start_date' => $startDate,
                'global_end_date' => $endDate,
                'global_period' => $period,
                'global_custom_month' => $customMonth,
                'global_custom_year' => $customYear
            ]);
        } else {
            session()->forget(['global_start_date', 'global_end_date', 'global_period', 'global_custom_month', 'global_custom_year']);
        }

        return back();
    })->name('set-period');

    // Dashboard (Breeze default disatukan dengan akuntansi.dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Klasifikasi
    Route::resource('klasifikasi', KlasifikasiController::class)->names('akuntansi.klasifikasi');

    Route::get('/kategori',            [KategoriAkunController::class, 'index'])->name('akuntansi.kategori');
    Route::post('/kategori',           [KategoriAkunController::class, 'store'])->name('akuntansi.kategori.store');
    Route::put('/kategori/{id}',       [KategoriAkunController::class, 'update'])->name('akuntansi.kategori.update');
    Route::delete('/kategori/{id}',    [KategoriAkunController::class, 'destroy'])->name('akuntansi.kategori.destroy');

    // Saldo Awal (Nama route dipertahankan)
    Route::get('/saldo-awal',          [SaldoAwalController::class, 'index'])->name('akuntansi.saldoawal');
    Route::post('/saldo-awal',         [SaldoAwalController::class, 'store'])->name('akuntansi.saldoawal.store');
    Route::get('/saldo-awal/{kodeAkun}/edit', [SaldoAwalController::class, 'edit'])->name('akuntansi.saldoawal.edit');
    Route::put('/saldo-awal/{kodeAkun}', [SaldoAwalController::class, 'update'])->name('akuntansi.saldoawal.update');
    Route::delete('/saldo-awal/{kodeAkun}', [SaldoAwalController::class, 'destroy'])->name('akuntansi.saldoawal.destroy');
    Route::post('/saldo-awal/bulk-update', [SaldoAwalController::class, 'updateBulk'])->name('akuntansi.saldoawal.bulk');

    // Jurnal Umum (Nama route dipertahankan)
    Route::get('/jurnal',              [JurnalController::class, 'index'])->name('akuntansi.jurnal');
    Route::post('/jurnal',             [JurnalController::class, 'store'])->name('akuntansi.jurnal.store');
    Route::put('/jurnal/{jurnal}',      [JurnalController::class, 'update'])->name('akuntansi.jurnal.update');
    Route::get('/jurnal/{jurnal}/details', [JurnalController::class, 'details'])->name('akuntansi.jurnal.details');
    Route::delete('/jurnal/{jurnal}',   [JurnalController::class, 'destroy'])->name('akuntansi.jurnal.destroy');
    Route::post('/reset',              [JurnalController::class, 'reset'])->name('akuntansi.reset');

    // Buku Besar & Neraca Saldo
    Route::get('/buku-besar/export-pdf',   [BukuBesarController::class, 'exportPdf'])->name('akuntansi.bukubesar.pdf');
    Route::get('/buku-besar/export-excel', [BukuBesarController::class, 'exportExcel'])->name('akuntansi.bukubesar.excel');
    Route::get('/buku-besar',          [BukuBesarController::class, 'index'])->name('akuntansi.bukubesar');
    Route::get('/neraca-saldo/export-pdf',   [NeracaSaldoController::class, 'exportPdf'])->name('akuntansi.neracasaldo.pdf');
    Route::get('/neraca-saldo/export-excel', [NeracaSaldoController::class, 'exportExcel'])->name('akuntansi.neracasaldo.excel');
    Route::get('/neraca-saldo',        [NeracaSaldoController::class, 'index'])->name('akuntansi.neracasaldo');

    // Jurnal Penyesuaian (Nama route dipertahankan)
    Route::get('/penyesuaian',             [PenyesuaianController::class, 'index'])->name('akuntansi.penyesuaian');
    Route::post('/penyesuaian',            [PenyesuaianController::class, 'store'])->name('akuntansi.penyesuaian.store');
    Route::get('/penyesuaian/{id}/details', [PenyesuaianController::class, 'getDetails'])->name('akuntansi.penyesuaian.details');
    Route::put('/penyesuaian/{id}',        [PenyesuaianController::class, 'update'])->name('akuntansi.penyesuaian.update');
    Route::delete('/penyesuaian/{id}',     [PenyesuaianController::class, 'destroy'])->name('akuntansi.penyesuaian.destroy');

    // Kertas Kerja & Laporan
    Route::get('/kertas-kerja',        [KertasKerjaController::class, 'index'])->name('akuntansi.kertaskerja');
    Route::get('/laporan-keuangan',    [LaporanKeuanganController::class, 'index'])->name('akuntansi.laporan.keuangan');
    Route::get('/laporan-keuangan/export/pdf', [LaporanKeuanganController::class, 'exportPdf'])->name('akuntansi.laporan.keuangan.pdf');
    Route::get('/laporan-keuangan/export/excel', [LaporanKeuanganController::class, 'exportExcel'])->name('akuntansi.laporan.keuangan.excel');

    // Jurnal Penutup (Nama route dipertahankan)
    Route::get('/jurnal-penutup',       [JurnalPenutupController::class, 'index'])->name('akuntansi.penutup');
    Route::post('/jurnal-penutup/generate', [JurnalPenutupController::class, 'generate'])->name('akuntansi.penutup.generate');
});

// ADMIN ONLY ROUTES
Route::middleware(['auth', 'admin.only'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', AdminUserController::class);
        Route::resource('roles', RoleController::class)->except(['show']);

        // Halaman dan Proses Akses Akun
        Route::get('akses-akun', [AksesAkunController::class, 'index'])->name('akses-akun.index');
        Route::post('akses-akun/sync', [AksesAkunController::class, 'sync'])->name('akses-akun.sync');
    });
});

require __DIR__.'/auth.php';
