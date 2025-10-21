<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KainKeluarController;
use App\Http\Controllers\PesananController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/kain-keluar', [KainKeluarController::class, 'index'])->name('orders.index');

// ========================
// DASHBOARD
// ========================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ========================
// KAIN KELUAR - SIMPLIFIKASI
// ========================

// Halaman utama daftar kain keluar
Route::get('/dashboardkainkeluar', [KainKeluarController::class, 'index'])->name('kainkeluar.index');

// API untuk data JSON
Route::get('/api/kainkeluar/list', [KainKeluarController::class, 'apiList'])->name('kainkeluar.api');

// Detail kain keluar
// routes/web.php
Route::get('/detailkainkeluar/{id}', [KainKeluarController::class, 'detailkainkeluar'])
     ->name('detailkainkeluar');

// ========================
// PESANAN
// ========================
Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
Route::get('/api/pesanan/{id_pesanan}/detail', [PesananController::class, 'getDetailPesanan'])->name('pesanan.detail');

// ========================
// PROFILE (AUTH)
// ========================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes utama
Route::get('/kain-keluar', [KainKeluarController::class, 'index'])->name('orders.index');
Route::get('/detailkainkeluar/{id}', [KainKeluarController::class, 'detailkainkeluar'])->name('detailkainkeluar');

// Routes alternatif (jika perlu)
Route::get('/kain-keluar/{id}', [KainKeluarController::class, 'show'])->name('orders.show');

// API Route
Route::get('/api/kain-keluar', [KainKeluarController::class, 'apiList'])->name('api.orders');

// Routes CRUD tambahan (opsional)
Route::get('/kain-keluar/create', [KainKeluarController::class, 'create'])->name('orders.create');
Route::post('/kain-keluar', [KainKeluarController::class, 'store'])->name('orders.store');
Route::get('/kain-keluar/{id}/edit', [KainKeluarController::class, 'edit'])->name('orders.edit');
Route::put('/kain-keluar/{id}', [KainKeluarController::class, 'update'])->name('orders.update');
Route::delete('/kain-keluar/{id}', [KainKeluarController::class, 'destroy'])->name('orders.destroy');

Route::post('/api/update-status', [AOrderController::class, 'updateStatus']);
Route::post('/kainkeluar/update-status/{id}', [KainKeluarController::class, 'updateStatus'])->name('kainkeluar.updateStatus');


Route::post('/api/update-status', [KainKeluarController::class, 'updateStatus']);

// routes/web.php
Route::get('/test-update-status', function() {
    // Test update status
    $testData = \App\Models\KainKeluar::first();
    return [
        'current_status' => $testData->status_layanan,
        'data_exists' => !!$testData
    ];
});

Route::get('/api/kainkeluar/list', [KainKeluarController::class, 'apiList']);

Route::get('/kainkeluar/list', [KainKeluarController::class, 'apiList']);
Route::post('/update-status', [KainKeluarController::class, 'updateStatus']);

Route::post('/api/orders/update-status', 'OrderController@updateStatus');

Route::post('/update-order-status', [OrderController::class, 'updateStatus']);

require __DIR__ . '/auth.php';