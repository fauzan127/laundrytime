<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\KainKeluarController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PesananController;

// ============
// DASHBOARD
// ============
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth_or_403'])->name('dashboard');

// ============
// AUTH PROFILE
// ============
Route::middleware('auth_or_403')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============
// GOOGLE LOGIN
// ============
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// ============
// KAIN KELUAR
// ============
Route::get('/dashboardkainkeluar', [KainKeluarController::class, 'index'])->name('kainkeluar.index');
Route::get('/detailkainkeluar/{id}', [KainKeluarController::class, 'detailkainkeluar'])->name('detailkainkeluar');

// API untuk ambil data (dipakai di fetch '/api/kainkeluar/list')
Route::get('/api/kainkeluar/list', [KainKeluarController::class, 'apiList'])->name('kainkeluar.api');

// API untuk update status
Route::post('/api/update-status', [KainKeluarController::class, 'updateStatus'])->name('kainkeluar.updateStatus');

// ============
// PESANAN (opsional)
// ============
// Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
// Route::get('/api/pesanan/{id_pesanan}/detail', [PesananController::class, 'getDetailPesanan'])->name('pesanan.detail');

// ============
// ERROR PAGES
// ============
Route::view('/403', 'errors.403');
Route::view('/404', 'errors.404');
Route::view('/503', 'errors.503');
Route::view('/500', 'errors.500');

Route::get('/kainkeluar', [KainKeluarController::class, 'index'])->name('kain_keluar.index');
Route::get('/kainkeluar/{id}', [KainKeluarController::class, 'detailkainkeluar'])->name('detailkainkeluar');

// Update status via AJAX
Route::post('/kainkeluar/update-status', [KainKeluarController::class, 'updateStatus'])->name('kain_keluar.updateStatus');

// --- View halaman utama dan detail ---
Route::get('/dashboard/kainkeluar', [OrdersController::class, 'index'])->name('dashboard.kainkeluar');
Route::get('/detailkainkeluar/{id}', [OrdersController::class, 'detail'])->name('detail.kainkeluar');

// --- API untuk JS ---
Route::get('/api/orders/list', [OrdersController::class, 'getOrders'])->name('api.orders.list');
Route::post('/api/update-status', [OrdersController::class, 'updateStatus'])->name('api.update.status');

use App\Http\Controllers\UpdateStatusController;

// Routes for UpdateStatusController (updatestatus.php)
Route::get('/api/status-options', [UpdateStatusController::class, 'getStatusOptions'])->name('api.status-options');
Route::post('/api/bulk-update-status', [UpdateStatusController::class, 'bulkUpdateStatus'])->name('api.bulk-update-status');

Route::get('/kainkeluar', [KainKeluarController::class, 'index'])->name('kain_keluar.index');
Route::get('/detailkainkeluar/{id}', [KainKeluarController::class, 'detailkainkeluar'])->name('kain_keluar.detail');
Route::post('/kainkeluar/update-status', [KainKeluarController::class, 'updateStatus'])->name('kain_keluar.updateStatus');
Route::get('/api/kainkeluar', [KainKeluarController::class, 'apiList'])->name('kain_keluar.api');
Route::post('/kainkeluar/store', [KainKeluarController::class, 'store'])->name('kain_keluar.store');
Route::put('/kainkeluar/update/{id}', [KainKeluarController::class, 'update'])->name('kain_keluar.update');

// API untuk ambil list
Route::get('/api/kainkeluar/list', [KainKeluarController::class, 'apiList'])
    ->name('api.kainkeluar.list');

// API untuk update status
Route::post('/api/kainkeluar/update-status', [KainKeluarController::class, 'updateStatus'])
    ->name('api.kainkeluar.updateStatus');

Route::get('/kainkeluar/list', [KainKeluarController::class, 'apiList']);

require __DIR__ . '/auth.php';
