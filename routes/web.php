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
use Illuminate\Support\Facades\Route;

// ==========================================
// PUBLIC ROUTES
// ==========================================
Route::get('/', function () {
    return view('landingpage');
});

// ==========================================
// AUTHENTICATED ROUTES (ALL USERS)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // Dashboard (Breeze default disatukan dengan akuntansi.dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('akuntansi.dashboard');
    // Jika ada sistem lain yang butuh name('dashboard') bawaan breeze, bisa tambahkan alias atau sesuaikan di redirect.

    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Modul Akuntansi Utama
    Route::resource('klasifikasi', KlasifikasiController::class)->names('akuntansi.klasifikasi');

    // Saldo Awal
    Route::prefix('saldo-awal')->name('akuntansi.saldoawal.')->group(function () {
        Route::get('/', [SaldoAwalController::class, 'index'])->name('index'); // Menggantikan akuntansi.saldoawal
        Route::post('/', [SaldoAwalController::class, 'store'])->name('store');
        Route::get('/{kodeAkun}/edit', [SaldoAwalController::class, 'edit'])->name('edit');
        Route::put('/{kodeAkun}', [SaldoAwalController::class, 'update'])->name('update');
        Route::delete('/{kodeAkun}', [SaldoAwalController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-update', [SaldoAwalController::class, 'updateBulk'])->name('bulk');
    });

    // Jurnal Umum
    Route::prefix('jurnal')->name('akuntansi.jurnal.')->group(function () {
        Route::get('/', [JurnalController::class, 'index'])->name('index'); // Menggantikan akuntansi.jurnal
        Route::post('/', [JurnalController::class, 'store'])->name('store');
        Route::put('/{jurnal}', [JurnalController::class, 'update'])->name('update');
        Route::get('/{jurnal}/details', [JurnalController::class, 'details'])->name('details');
        Route::delete('/{jurnal}', [JurnalController::class, 'destroy'])->name('destroy');
    });
    // Reset Jurnal Umum (Diluar prefix jurnal tapi berhubungan)
    Route::post('/reset', [JurnalController::class, 'reset'])->name('akuntansi.reset');

    // Buku Besar & Neraca Saldo
    Route::get('/buku-besar', [BukuBesarController::class, 'index'])->name('akuntansi.bukubesar');
    Route::get('/neraca-saldo', [NeracaSaldoController::class, 'index'])->name('akuntansi.neracasaldo');

    // Jurnal Penyesuaian
    Route::prefix('penyesuaian')->name('akuntansi.penyesuaian.')->group(function () {
        Route::get('/', [PenyesuaianController::class, 'index'])->name('index'); // Menggantikan akuntansi.penyesuaian
        Route::post('/', [PenyesuaianController::class, 'store'])->name('store');
        Route::get('/{id}/details', [PenyesuaianController::class, 'getDetails'])->name('details');
        Route::put('/{id}', [PenyesuaianController::class, 'update'])->name('update');
        Route::delete('/{id}', [PenyesuaianController::class, 'destroy'])->name('destroy');
    });

    // Kertas Kerja & Laporan
    Route::get('/kertas-kerja', [KertasKerjaController::class, 'index'])->name('akuntansi.kertaskerja');
    Route::get('/laporan-keuangan', [LaporanKeuanganController::class, 'index'])->name('akuntansi.laporan.keuangan');

    // Jurnal Penutup
    Route::prefix('jurnal-penutup')->name('akuntansi.penutup.')->group(function () {
        Route::get('/', [JurnalPenutupController::class, 'index'])->name('index'); // Menggantikan akuntansi.penutup
        Route::post('/generate', [JurnalPenutupController::class, 'generate'])->name('generate');
    });
});

// ==========================================
// ADMIN ONLY ROUTES
// ==========================================
Route::middleware(['auth', 'admin.only'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', AdminUserController::class);
        Route::resource('roles', RoleController::class)->except(['show']);

        Route::post('akses-akun/sync', [AksesAkunController::class, 'sync'])->name('akses-akun.sync');
    });
});

require __DIR__.'/auth.php';
