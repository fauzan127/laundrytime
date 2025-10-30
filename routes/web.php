<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\AdminTransactionController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard utama
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth_or_403'])->name('dashboard');

// Routes untuk Profile
Route::middleware('auth_or_403')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes utama aplikasi (butuh login)
Route::middleware('auth_or_403')->group(function () {
    // Manajemen Order
    Route::resource('order', OrderController::class);
});

// Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


// Auth routes bawaan Laravel Breeze/Fortify

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/report', [DashboardController::class, 'report'])->name('dashboard.report');
});

Route::post('/payment/callback', [PaymentCallbackController::class, 'handle']);
Route::post('/test-callback', function () {
    return response()->json(['message' => 'Callback OK']);
});

Route::middleware('auth_or_403')->group(function () {
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/payment/{id}', [PaymentController::class, 'pay'])->name('payment.pay');
    
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/payment', [AdminTransactionController::class, 'index'])->name('admin.payment');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/payment', [AdminTransactionController::class, 'index'])->name('admin.payment');
    Route::post('/admin/payment/{id}/mark-paid', [AdminTransactionController::class, 'markPaid'])->name('admin.payment.mark-paid');
    Route::post('/admin/payment/{id}/mark-unpaid', [AdminTransactionController::class, 'markUnpaid'])->name('admin.payment.mark-unpaid');
});

require __DIR__.'/auth.php';
