<?php

use App\Http\Controllers\KainKeluarController;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('index returns view with orders data', function () {
    // Arrange
    $orders = Order::factory()->count(3)->create();

    // Act
    $response = $this->get(route('kain_keluar.index'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('dashboardkainkeluar');
    $response->assertViewHas('data');
    expect($response->viewData('data'))->toHaveCount(3);
});

test('updateStatus updates order status and returns success JSON', function () {
    // Arrange
    $order = Order::factory()->create(['status' => 'diproses']);

    // Act
    $response = $this->post(route('api.kainkeluar.updateStatus'), [
        'order_id' => $order->id,
        'status' => 'antar'
    ]);

    // Assert
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Status berhasil diperbarui.',
        'updated_status' => 'antar'
    ]);
    $order->refresh();
    expect($order->status)->toBe('antar');
});

test('updateStatus returns error for invalid order_id', function () {
    // Act
    $response = $this->post(route('api.kainkeluar.updateStatus'), [
        'order_id' => 999,
        'status' => 'diproses'
    ]);

    // Assert
    $response->assertStatus(302); // Redirect back with validation errors
    $response->assertSessionHasErrors('order_id');
});

test('detailkainkeluar returns view with order data', function () {
    // Arrange
    $order = Order::factory()->create();

    // Act
    $response = $this->get(route('kain_keluar.detail', $order->id));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('detailkainkeluar');
    $response->assertViewHas('data');
    expect($response->viewData('data')->id)->toBe($order->id);
});

test('detailkainkeluar returns 404 for non-existent order', function () {
    // Act
    $response = $this->get(route('kain_keluar.detail', 999));

    // Assert
    $response->assertStatus(404);
});

test('apiList returns JSON with all orders', function () {
    // Arrange
    $orders = Order::factory()->count(2)->create();

    // Act
    $response = $this->get(route('api.kainkeluar.list'));

    // Assert
    $response->assertStatus(200);
    $response->assertJsonCount(2);
});

test('store creates new order and redirects with success', function () {
    // Arrange
    $user = \App\Models\User::factory()->create();

    // Act
    $response = $this->actingAs($user)->post(route('kain_keluar.store'), [
        'nama_pelanggan' => 'John Doe',
        'no_hp' => '123456789',
        'layanan' => 'antar_jemput',
        'berat' => 5.5,
        'status' => 'diproses',
        'alamat' => '123 Main St',
        'catatan' => 'Urgent'
    ]);

    // Assert
    $response->assertRedirect(route('kain_keluar.index'));
    $response->assertSessionHas('success', 'Data berhasil ditambahkan.');
    expect(Order::where('customer_name', 'John Doe')->exists())->toBeTrue();
});

test('update modifies existing order and redirects with success', function () {
    // Arrange
    $order = Order::factory()->create(['customer_name' => 'Old Name']);

    // Act
    $response = $this->put(route('kain_keluar.update', $order->id), [
        'nama_pelanggan' => 'New Name',
        'no_hp' => '987654321',
        'layanan' => 'antar_jemput',
        'berat' => 3.0,
        'status' => 'antar',
        'alamat' => '456 Elm St',
        'catatan' => 'Handle with care'
    ]);

    // Assert
    $response->assertRedirect(route('kain_keluar.index'));
    $response->assertSessionHas('success', 'Data berhasil diperbarui.');
    $order->refresh();
    expect($order->customer_name)->toBe('New Name');
});

test('update returns 404 for non-existent order', function () {
    // Act
    $response = $this->put(route('kain_keluar.update', 999), [
        'nama_pelanggan' => 'Test',
        'no_hp' => '123',
        'layanan' => 'Test',
        'berat' => 1,
        'status' => 'diproses',
        'alamat' => 'Test',
        'catatan' => 'Test'
    ]);

    // Assert
    $response->assertStatus(404);
});
