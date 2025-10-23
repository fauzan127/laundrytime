<?php

use App\Http\Controllers\PaymentCallbackController;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    // Mock Log facade
    Log::shouldReceive('info');
    Log::shouldReceive('warning');
});

test('PaymentCallbackController dapat diinstansiasi', function () {
    $controller = new PaymentCallbackController();
    expect($controller)->toBeInstanceOf(PaymentCallbackController::class);
});

test('PaymentCallbackController memiliki method handle', function () {
    $controller = new PaymentCallbackController();
    expect(method_exists($controller, 'handle'))->toBeTrue();
});

test('Method handle signature benar', function () {
    $controller = new PaymentCallbackController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('handle');
    
    expect($method->isPublic())->toBeTrue()
        ->and($method->getNumberOfParameters())->toBe(1)
        ->and($method->getParameters()[0]->getType()->getName())->toBe('Illuminate\Http\Request');
});

test('Method handle menggunakan env MIDTRANS_SERVER_KEY', function () {
    $controller = new PaymentCallbackController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('handle');
    
    $sourceCode = file($method->getFileName());
    $methodCode = implode('', array_slice($sourceCode, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
    
    expect(str_contains($methodCode, "env('MIDTRANS_SERVER_KEY')"))->toBeTrue();
});

test('Method handle melakukan validasi signature key', function () {
    $controller = new PaymentCallbackController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('handle');
    
    $sourceCode = file($method->getFileName());
    $methodCode = implode('', array_slice($sourceCode, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
    
    expect(str_contains($methodCode, "hash('sha512'"))->toBeTrue()
        ->and(str_contains($methodCode, '$expectedSignature !== $signatureKey'))->toBeTrue()
        ->and(str_contains($methodCode, "response()->json(['message' => 'Invalid signature'], 403)"))->toBeTrue();
});

test('Method handle mengekstrak order ID dari format Midtrans', function () {
    $controller = new PaymentCallbackController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('handle');
    
    $sourceCode = file($method->getFileName());
    $methodCode = implode('', array_slice($sourceCode, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
    
    expect(str_contains($methodCode, "explode('-', \$orderId)"))->toBeTrue()
        ->and(str_contains($methodCode, "\$idParts[1]"))->toBeTrue();
});

test('Method handle mencari order dengan relasi payment', function () {
    $controller = new PaymentCallbackController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('handle');
    
    $sourceCode = file($method->getFileName());
    $methodCode = implode('', array_slice($sourceCode, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
    
    expect(str_contains($methodCode, "Order::with('payment')->find(\$orderIdReal)"))->toBeTrue();
});

test('Method handle update status payment untuk transaksi sukses', function () {
    $controller = new PaymentCallbackController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('handle');
    
    $sourceCode = file($method->getFileName());
    $methodCode = implode('', array_slice($sourceCode, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
    
    expect(str_contains($methodCode, "in_array(\$transactionStatus, ['capture', 'settlement'])"))->toBeTrue()
        ->and(str_contains($methodCode, "'payment_status' => 'Sudah Dibayar'"))->toBeTrue()
        ->and(str_contains($methodCode, "'paid_at' => now()"))->toBeTrue();
});

test('Method handle memiliki logging untuk berbagai skenario', function () {
    $controller = new PaymentCallbackController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('handle');
    
    $sourceCode = file($method->getFileName());
    $methodCode = implode('', array_slice($sourceCode, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
    
    expect(str_contains($methodCode, "Log::info('📥 Callback masuk:', \$request->all())"))->toBeTrue()
        ->and(str_contains($methodCode, 'Log::warning'))->toBeTrue()
        ->and(str_contains($methodCode, 'Log::info("✅ Pembayaran berhasil'))->toBeTrue()
        ->and(str_contains($methodCode, 'Log::warning("⚠️ Order'))->toBeTrue()
        ->and(str_contains($methodCode, 'Log::info("ℹ️ Transaksi'))->toBeTrue();
});

test('Method handle mengembalikan response JSON untuk setiap kasus', function () {
    $controller = new PaymentCallbackController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('handle');
    
    $sourceCode = file($method->getFileName());
    $methodCode = implode('', array_slice($sourceCode, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
    
    expect(str_contains($methodCode, "response()->json(['message' => 'Invalid signature'], 403)"))->toBeTrue()
        ->and(str_contains($methodCode, "response()->json(['message' => 'Order ID tidak valid'], 400)"))->toBeTrue()
        ->and(str_contains($methodCode, "response()->json(['message' => 'Order tidak ditemukan'], 404)"))->toBeTrue()
        ->and(str_contains($methodCode, "response()->json(['message' => 'Callback diproses'], 200)"))->toBeTrue();
});

test('Method handle memiliki conditional untuk order tanpa payment', function () {
    $controller = new PaymentCallbackController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('handle');
    
    $sourceCode = file($method->getFileName());
    $methodCode = implode('', array_slice($sourceCode, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
    
    expect(str_contains($methodCode, "if (\$order->payment)"))->toBeTrue()
        ->and(str_contains($methodCode, "} else {"))->toBeTrue();
});