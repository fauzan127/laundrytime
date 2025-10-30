<?php

use App\Http\Controllers\TrackingController;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

test('index returns view with orders excluding Diproses status', function () {
    $controller = new TrackingController();

    $mockOrders = collect([
        (object)['id' => 1, 'status' => 'Antar Jemput'],
        (object)['id' => 2, 'status' => 'Cuci'],
    ]);

    Order::shouldReceive('where')
        ->with('status', '!=', 'Diproses')
        ->andReturnSelf();
    Order::shouldReceive('orderBy')
        ->with('created_at', 'asc')
        ->andReturnSelf();
    Order::shouldReceive('get')
        ->andReturn($mockOrders);

    $response = $controller->index();

    expect($response)->toBeInstanceOf(\Illuminate\View\View::class);
});

test('updateStatus updates order status successfully', function () {
    $controller = new TrackingController();

    $request = Request::create('', 'PUT', ['status' => 'Cuci']);
    $mockOrder = Mockery::mock(Order::class);
    $mockOrder->shouldReceive('save')->once();

    Order::shouldReceive('find')
        ->with(1)
        ->andReturn($mockOrder);

    $response = $controller->updateStatus($request, 1);

    expect($response->getData()->success)->toBeTrue();
});

test('updateStatus fails when order not found', function () {
    $controller = new TrackingController();

    $request = Request::create('', 'PUT', ['status' => 'Cuci']);

    Order::shouldReceive('find')
        ->with(1)
        ->andReturn(null);

    $response = $controller->updateStatus($request, 1);

    expect($response->getData()->success)->toBeFalse();
    expect($response->getData()->message)->toBe('Order tidak ditemukan');
});

test('apiList returns JSON with all orders', function () {
    $controller = new TrackingController();

    $mockOrders = collect([
        (object)['id' => 1, 'customer_name' => 'John'],
    ]);

    Order::shouldReceive('orderBy')
        ->with('created_at', 'desc')
        ->andReturnSelf();
    Order::shouldReceive('get')
        ->andReturn($mockOrders);

    $response = $controller->apiList();

    expect($response->getData()->success)->toBeTrue();
    expect($response->getData()->data)->toBe($mockOrders);
});

test('show returns view with orders by customer name', function () {
    $controller = new TrackingController();

    $mockOrders = collect([
        (object)['id' => 1, 'customer_name' => 'John'],
    ]);

    Order::shouldReceive('where')
        ->with('customer_name', 'John')
        ->andReturnSelf();
    Order::shouldReceive('get')
        ->andReturn($mockOrders);

    $response = $controller->show('John');

    expect($response)->toBeInstanceOf(\Illuminate\View\View::class);
});
