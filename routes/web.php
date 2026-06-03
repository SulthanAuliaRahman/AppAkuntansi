<?php

use App\Http\Controllers\BukuBesarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\JurnalPenutupController;
use App\Http\Controllers\KertasKerjaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NeracaSaldoController;
use App\Http\Controllers\PenyesuaianController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Dashboard (halaman utama)
Route::get('/', [DashboardController::class, 'index'])->name('akuntansi.dashboard');

// Jurnal Umum
Route::get('/jurnal',              [JurnalController::class, 'index'])->name('akuntansi.jurnal');
Route::post('/jurnal',             [JurnalController::class, 'store'])->name('akuntansi.jurnal.store');
Route::delete('/jurnal/{jurnal}',   [JurnalController::class, 'destroy'])->name('akuntansi.jurnal.destroy');
Route::post('/reset',              [JurnalController::class, 'reset'])->name('akuntansi.reset');

// Buku Besar
Route::get('/buku-besar',          [BukuBesarController::class, 'index'])->name('akuntansi.bukubesar');

// Neraca Saldo
Route::get('/neraca-saldo',        [NeracaSaldoController::class, 'index'])->name('akuntansi.neracasaldo');

// Jurnal Penyesuaian
Route::get('/penyesuaian',         [PenyesuaianController::class, 'index'])->name('akuntansi.penyesuaian');
Route::post('/penyesuaian/toggle', [PenyesuaianController::class, 'toggle'])->name('akuntansi.penyesuaian.toggle');

// Kertas Kerja
Route::get('/kertas-kerja',        [KertasKerjaController::class, 'index'])->name('akuntansi.kertaskerja');

// Laporan Keuangan
Route::get('/laporan',             [LaporanController::class, 'index'])->name('akuntansi.laporan');

// Jurnal Penutup
Route::get('/jurnal-penutup',      [JurnalPenutupController::class, 'index'])->name('akuntansi.penutup');

// Auth routes (dari Laravel Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
