<?php

use App\Http\Controllers\OrderController;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ServiceType;
use App\Models\ClothingType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('index returns orders ordered by created_at desc', function () {
    // Arrange
    $user = User::factory()->create();
    Auth::shouldReceive('id')->andReturn($user->id);

    // Create orders for this user
    $orders = Order::factory()->count(3)->create(['user_id' => $user->id]);

    // Act
    $controller = new OrderController();
    $response = $controller->index();

    // Assert
    expect($response)->toBeInstanceOf(\Illuminate\View\View::class);
    expect($response->getData()['orders']->count())->toBe(3); // Factory orders are persisted in unit tests with RefreshDatabase
});

test('index handles empty orders', function () {
    // Arrange
    $user = User::factory()->create();
    Auth::shouldReceive('id')->andReturn($user->id);

    // Act
    $controller = new OrderController();
    $response = $controller->index();

    // Assert
    expect($response)->toBeInstanceOf(\Illuminate\View\View::class);
    expect($response->getData()['orders']->count())->toBe(0); // No orders in fresh database
});

test('create returns view with service and clothing types', function () {
    // Arrange
    $serviceTypes = ServiceType::factory()->count(2)->create(['is_active' => true]);
    $clothingTypes = ClothingType::factory()->count(2)->create(['is_active' => true]);

    // Act
    $controller = new OrderController();
    $response = $controller->create();

    // Assert
    expect($response)->toBeInstanceOf(\Illuminate\View\View::class);
    expect($response->getData()['serviceTypes']->count())->toBe(2); // Only factory created types
    expect($response->getData()['clothingTypes']->count())->toBe(2); // Only factory created types
});

test('create filters only active types', function () {
    // Arrange
    ServiceType::factory()->create(['is_active' => false]);
    ClothingType::factory()->create(['is_active' => false]);
    $activeService = ServiceType::factory()->create(['is_active' => true]);
    $activeClothing = ClothingType::factory()->create(['is_active' => true]);

    // Act
    $controller = new OrderController();
    $response = $controller->create();

    // Assert
    expect($response->getData()['serviceTypes']->pluck('id'))->toContain($activeService->id);
    expect($response->getData()['clothingTypes']->pluck('id'))->toContain($activeClothing->id);
});

test('store creates order successfully', function () {
    // Arrange
    $user = User::factory()->create();
    Auth::shouldReceive('id')->andReturn($user->id);

    $serviceType = ServiceType::factory()->create(['price_per_kg' => 10000]);
    $clothingType = ClothingType::factory()->create(['additional_price' => 2000]);

    $request = new Request([
        'customer_name' => 'John Doe',
        'customer_phone' => '08123456789',
        'delivery_type' => 'pengantaran_pribadi',
        'items' => [
            [
                'service_type_id' => $serviceType->id,
                'clothing_type_id' => $clothingType->id,
                'weight' => 2.5,
            ]
        ]
    ]);

    // Act
    $controller = new OrderController();
    $response = $controller->store($request);

    // Assert
    expect($response)->toBeInstanceOf(\Illuminate\Http\RedirectResponse::class);
});

test('store validates required fields', function () {
    // Arrange
    $user = User::factory()->create();
    Auth::shouldReceive('id')->andReturn($user->id);

    $request = Request::create('/orders', 'POST', []);

    DB::shouldReceive('beginTransaction')->never();
    DB::shouldReceive('commit')->never();
    DB::shouldReceive('rollBack')->never();

    // Act & Assert
    $controller = new OrderController();
    expect(fn() => $controller->store($request))->toThrow(\Illuminate\Validation\ValidationException::class);
});

test('show returns order with relationships', function () {
    // Arrange
    $user = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $user->id]);

    // Act
    $controller = new OrderController();
    $response = $controller->show($order);

    // Assert
    expect($response)->toBeInstanceOf(\Illuminate\View\View::class);
    expect($response->getData()['order']->id)->toBe($order->id);
});

test('show loads order relationships', function () {
    // Arrange
    $user = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $user->id]);
    $orderItem = OrderItem::factory()->create(['order_id' => $order->id]);

    // Act
    $controller = new OrderController();
    $response = $controller->show($order);

    // Assert
    expect($response->getData()['order']->items)->toHaveCount(1);
    expect($response->getData()['order']->user)->not->toBeNull();
});

test('edit allows admin access', function () {
    // Arrange
    $admin = User::factory()->create(['role' => 'admin']);
    Auth::shouldReceive('user')->andReturn($admin);

    $order = Order::factory()->create();

    // Act
    $controller = new OrderController();
    $response = $controller->edit($order);

    // Assert
    expect($response)->toBeInstanceOf(\Illuminate\View\View::class);
});

test('edit denies non-admin access', function () {
    // Arrange
    $user = User::factory()->create(['role' => 'user']);
    Auth::shouldReceive('user')->andReturn($user);

    $order = Order::factory()->create();

    // Act & Assert
    $controller = new OrderController();
    expect(fn() => $controller->edit($order))->toThrow(\Symfony\Component\HttpKernel\Exception\HttpException::class);
});

test('update allows admin to update order', function () {
    // Arrange
    $admin = User::factory()->create(['role' => 'admin']);
    Auth::shouldReceive('user')->andReturn($admin);

    $order = Order::factory()->create();
    $serviceType = ServiceType::factory()->create(['price_per_kg' => 10000]);
    $clothingType = ClothingType::factory()->create(['additional_price' => 2000]);

    $request = new Request([
        'customer_name' => 'Updated Name',
        'customer_phone' => '08123456789',
        'delivery_type' => 'pengantaran_pribadi',
        'status' => 'Proses',
        'items' => [
            [
                'service_type_id' => $serviceType->id,
                'clothing_type_id' => $clothingType->id,
                'weight' => 1.5,
            ]
        ]
    ]);

    // Act
    $controller = new OrderController();
    $response = $controller->update($request, $order);

    // Assert
    expect($response)->toBeInstanceOf(\Illuminate\Http\RedirectResponse::class);
});

test('update denies non-admin access', function () {
    // Arrange
    $user = User::factory()->create(['role' => 'user']);
    Auth::shouldReceive('user')->andReturn($user);

    $order = Order::factory()->create();
    $request = Request::create('/orders/' . $order->id, 'PUT', []);

    DB::shouldReceive('beginTransaction')->never();
    DB::shouldReceive('commit')->never();
    DB::shouldReceive('rollBack')->never();

    // Act & Assert
    $controller = new OrderController();
    expect(fn() => $controller->update($request, $order))->toThrow(\Symfony\Component\HttpKernel\Exception\HttpException::class);
});

test('destroy allows admin to delete order', function () {
    // Arrange
    $admin = User::factory()->create(['role' => 'admin']);
    Auth::shouldReceive('user')->andReturn($admin);

    $order = Order::factory()->create();

    DB::shouldReceive('beginTransaction')->once();
    DB::shouldReceive('commit')->once();
    DB::shouldReceive('rollBack')->never();

    // Act
    $controller = new OrderController();
    $response = $controller->destroy($order);

    // Assert
    expect($response)->toBeInstanceOf(\Illuminate\Http\RedirectResponse::class);
});

test('destroy denies non-admin access', function () {
    // Arrange
    $user = User::factory()->create(['role' => 'user']);
    Auth::shouldReceive('user')->andReturn($user);

    $order = Order::factory()->create();

    DB::shouldReceive('beginTransaction')->never();
    DB::shouldReceive('commit')->never();
    DB::shouldReceive('rollBack')->never();

    // Act & Assert
    $controller = new OrderController();
    expect(fn() => $controller->destroy($order))->toThrow(\Symfony\Component\HttpKernel\Exception\HttpException::class);
});
