<?php

use App\Http\Controllers\UpdateStatusController;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

// Tests for updateStatus method
it('updates order status successfully', function () {
    $order = Order::factory()->create(['status' => 'diproses']);
    $orderId = $order->id;

    $controller = new UpdateStatusController();

    $request = new Request([
        'order_id' => $orderId,
        'status' => 'sampal_tujuan'
    ]);

    $response = $controller->updateStatus($request);
    $responseData = $response->getData(true);

    expect($response->getStatusCode())->toBe(200);
    expect($responseData['success'])->toBe(true);
    expect($responseData['message'])->toBe('Status berhasil diupdate');
    expect($responseData['data']['order_id'])->toBe($orderId);
    expect($responseData['data']['status'])->toBe('sampal_tujuan');
});

it('fails to update order status with invalid data', function () {
    $controller = new UpdateStatusController();

    $request = new Request([
        'order_id' => 'invalid',
        'status' => ''
    ]);

    $response = $controller->updateStatus($request);
    $responseData = $response->getData(true);

    expect($response->getStatusCode())->toBe(422);
    expect($responseData['success'])->toBe(false);
});

// Tests for getStatusOptions method
it('returns status options successfully', function () {
    $controller = new UpdateStatusController();

    $response = $controller->getStatusOptions();
    $responseData = $response->getData(true);

    expect($response->getStatusCode())->toBe(200);
    expect($responseData['success'])->toBe(true);
    expect($responseData['data'])->toBeArray();
});

// Tests for bulkUpdateStatus method
it('bulk updates status successfully', function () {
    $orders = Order::factory()->count(3)->create(['status' => 'diproses']);
    $orderIds = $orders->pluck('id')->toArray();
    $controller = new UpdateStatusController();

    $request = new Request([
        'order_ids' => $orderIds,
        'status' => 'sampal_tujuan'
    ]);

    $response = $controller->bulkUpdateStatus($request);
    $responseData = $response->getData(true);

    expect($response->getStatusCode())->toBe(200);
    expect($responseData['success'])->toBe(true);
    expect($responseData['message'])->toContain('Berhasil mengupdate');
});

it('fails bulk update with invalid data', function () {
    $controller = new UpdateStatusController();

    $request = new Request([
        'order_ids' => [],
        'status' => ''
    ]);

    $response = $controller->bulkUpdateStatus($request);
    $responseData = $response->getData(true);

    expect($response->getStatusCode())->toBe(422);
    expect($responseData['success'])->toBe(false);
});
