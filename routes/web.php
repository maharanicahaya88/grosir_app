<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\KonsumenController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PembayaranController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', fn() => redirect()->route('pemesanan.index'))->name('home');

    // Master Data
    Route::resource('konsumen', KonsumenController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('barang',   BarangController::class);
    Route::get('api/barang/{barang}/harga', [PemesananController::class, 'getHargaBarang'])->name('api.barang.harga');

    // Pemesanan
    Route::resource('pemesanan', PemesananController::class);
    Route::prefix('pemesanan')->name('pemesanan.')->group(function () {
        Route::patch('{pemesanan}/approve',  [PemesananController::class, 'approve'])    ->name('approve');
        Route::get('{pemesanan}/cetak-pdf',  [PemesananController::class, 'cetakPdf'])   ->name('cetak-pdf');
        Route::get('export/excel',           [PemesananController::class, 'exportExcel'])->name('export-excel');
    });

    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('pemesanan',      [LaporanController::class, 'pemesanan'])    ->name('pemesanan');
        Route::get('pemesanan/pdf',  [LaporanController::class, 'pemesananPdf']) ->name('pemesanan.pdf');
    });

    // Pembayaran
    Route::resource('pembayaran', PembayaranController::class)
        ->except(['edit', 'update']);
}); // ← tutup middleware group

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');