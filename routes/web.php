<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\AdminTransactionController;
use App\Http\Controllers\TrackingController;

Route::get('/', function () {
    return view('welcome');
});

// Routes untuk Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile completion routes
    Route::get('/profile/complete', [ProfileController::class, 'showProfileCompletionForm'])->name('profile.complete');
    Route::post('/profile/complete', [ProfileController::class, 'postProfileCompletion'])->name('profile.complete.post');
});

// Routes utama aplikasi (butuh login)
Route::middleware('auth')->group(function () {
    // Manajemen Order
    Route::resource('order', OrderController::class);
});

// Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/report', [DashboardController::class, 'report'])->name('dashboard.report');
    Route::get('/dashboard/report/export', [DashboardController::class, 'exportPdf'])->name('dashboard.report.export');
    Route::get('/dashboard/tracking', [TrackingController::class, 'index'])->name('tracking.index');
    Route::get('/dashboard/tracking/{customerName}', [TrackingController::class, 'show'])->name('tracking.show');
    Route::post('/dashboard/tracking/{id}/status', [TrackingController::class, 'updateStatus'])->name('tracking.updateStatus');
    Route::get('/completed-orders', [TrackingController::class, 'completedOrders'])->name('tracking.completed');
});

Route::post('/payment/callback', [PaymentCallbackController::class, 'handle']);
Route::post('/test-callback', function () {
    return response()->json(['message' => 'Callback OK']);
});

Route::middleware('auth',)->group(function () {
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/payment/{id}', [PaymentController::class, 'pay'])->name('payment.pay');
    Route::get('/payment/check-status', [PaymentController::class, 'checkStatus'])->name('payment.checkStatus');
    
});


// Admin Payment Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/payment', [AdminTransactionController::class, 'index'])->name('admin.payment');
    Route::post('/admin/payment/{id}/mark-paid', [AdminTransactionController::class, 'markPaid'])->name('admin.payment.mark-paid');
    Route::post('/admin/payment/{id}/mark-unpaid', [AdminTransactionController::class, 'markUnpaid'])->name('admin.payment.mark-unpaid');
});

require __DIR__.'/auth.php';

Route::get('/test-session', function () {
    session(['login_test' => 'ok123']);
    return response()->json([
        'session' => session()->all(),
        'cookie' => request()->cookie()
    ]);
});
