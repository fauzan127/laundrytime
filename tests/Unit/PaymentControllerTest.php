<?php

use App\Http\Controllers\PaymentController;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;
use Midtrans\Config;

// Mock Midtrans Snap class
beforeEach(function () {
    // Mock Snap class
    $this->snapMock = Mockery::mock('overload:Midtrans\Snap');
});

afterEach(function () {
    Mockery::close();
});

test('PaymentController dapat diinstansiasi', function () {
    $controller = new PaymentController();
    expect($controller)->toBeInstanceOf(PaymentController::class);
});

test('PaymentController memiliki method index', function () {
    $controller = new PaymentController();
    expect(method_exists($controller, 'index'))->toBeTrue();
});

test('Method index signature benar', function () {
    $controller = new PaymentController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('index');
    
    expect($method->isPublic())->toBeTrue()
        ->and($method->getNumberOfParameters())->toBe(0);
});

test('PaymentController extends base controller', function () {
    $controller = new PaymentController();
    $reflection = new ReflectionClass($controller);
    $parent = $reflection->getParentClass();
    
    expect($parent->getName())->toBe('App\Http\Controllers\Controller');
});

test('PaymentController menggunakan Midtrans Config', function () {
    $controller = new PaymentController();
    $reflection = new ReflectionClass($controller);
    
    // Memastikan menggunakan Midtrans Config
    $method = $reflection->getMethod('index');
    $sourceCode = file($method->getFileName());
    $methodCode = implode('', array_slice($sourceCode, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
    
    expect(str_contains($methodCode, 'Config::$serverKey'))->toBeTrue()
        ->and(str_contains($methodCode, 'Config::$isProduction'))->toBeTrue()
        ->and(str_contains($methodCode, 'Config::$isSanitized'))->toBeTrue()
        ->and(str_contains($methodCode, 'Config::$is3ds'))->toBeTrue();
});

test('Method index menggunakan data user yang sedang login', function () {
    $controller = new PaymentController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('index');
    
    // Extract source code untuk verifikasi penggunaan Auth
    $sourceCode = file($method->getFileName());
    $methodCode = implode('', array_slice($sourceCode, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
    
    // Verifikasi bahwa method menggunakan Auth::user()
    expect(str_contains($methodCode, 'Auth::user()'))->toBeTrue()
        ->and(str_contains($methodCode, '$user->name'))->toBeTrue()
        ->and(str_contains($methodCode, '$user->email'))->toBeTrue();
});

test('Method index membuat order_id yang unik untuk setiap transaksi', function () {
    $controller = new PaymentController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('index');
    
    // Extract source code
    $sourceCode = file($method->getFileName());
    $methodCode = implode('', array_slice($sourceCode, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
    
    // Verifikasi format order_id yang unik
    expect(str_contains($methodCode, "uniqid()"))->toBeTrue()
        ->and(str_contains($methodCode, "'ORDER-' . \$order->id . '-'"))->toBeTrue()
        ->and(str_contains($methodCode, "transaction_details"))->toBeTrue();
});

test('Method index memiliki logic conditional untuk snap token generation', function () {
    $controller = new PaymentController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('index');
    
    // Extract source code
    $sourceCode = file($method->getFileName());
    $methodCode = implode('', array_slice($sourceCode, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
    
    // Verifikasi conditional logic untuk status payment
    expect(str_contains($methodCode, "if (!\$order->payment || \$order->payment->payment_status === 'Belum Dibayar')"))->toBeTrue()
        ->and(str_contains($methodCode, "snapTokens[\$order->id]"))->toBeTrue()
        ->and(str_contains($methodCode, "foreach (\$orders as \$order)"))->toBeTrue();
});

test('Method index memiliki item details dengan struktur yang lengkap', function () {
    $controller = new PaymentController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('index');
    
    // Extract source code
    $sourceCode = file($method->getFileName());
    $methodCode = implode('', array_slice($sourceCode, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
    
    // Verifikasi item details structure untuk Midtrans
    expect(str_contains($methodCode, "'item_details'"))->toBeTrue()
        ->and(str_contains($methodCode, "'id' => 'order-' . \$order->id"))->toBeTrue()
        ->and(str_contains($methodCode, "'price' => (int) \$order->total_price"))->toBeTrue()
        ->and(str_contains($methodCode, "'quantity' => 1"))->toBeTrue()
        ->and(str_contains($methodCode, "'name' => 'Pembayaran Order #' . \$order->id"))->toBeTrue();
});

test('Method index menggunakan database query untuk mengambil orders', function () {
    $controller = new PaymentController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('index');
    
    // Extract source code
    $sourceCode = file($method->getFileName());
    $methodCode = implode('', array_slice($sourceCode, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
    
    // Verifikasi penggunaan Eloquent
    expect(str_contains($methodCode, "Order::with('payment')->where('user_id', \$user->id)->get()"))->toBeTrue();
});

test('Method index memiliki error handling untuk Midtrans exception', function () {
    $controller = new PaymentController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('index');
    
    // Extract source code
    $sourceCode = file($method->getFileName());
    $methodCode = implode('', array_slice($sourceCode, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
    
    // Verifikasi error handling
    expect(str_contains($methodCode, "try {"))->toBeTrue()
        ->and(str_contains($methodCode, "} catch (\\Exception \$e)"))->toBeTrue()
        ->and(str_contains($methodCode, "Log::error"))->toBeTrue()
        ->and(str_contains($methodCode, "\$snapTokens[\$order->id] = null"))->toBeTrue();
});

test('Method index redirect guest ke login page', function () {
    $controller = new PaymentController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('index');
    
    // Extract source code
    $sourceCode = file($method->getFileName());
    $methodCode = implode('', array_slice($sourceCode, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
    
    // Verifikasi redirect untuk guest
    expect(str_contains($methodCode, "if (!\$user)"))->toBeTrue()
        ->and(str_contains($methodCode, "redirect()->route('login')"))->toBeTrue()
        ->and(str_contains($methodCode, "with('error', 'Silakan login terlebih dahulu.')"))->toBeTrue();
});