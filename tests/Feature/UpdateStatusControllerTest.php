<?php

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Tests for updateStatus method
it('updates order status successfully', function () {
    $order = Order::factory()->create(['status' => 'diproses']);

    $response = $this->postJson('/api/update-status', [
        'order_id' => $order->id,
        'status' => 'Diproses'
    ]);

    $response->assertStatus(200)
             ->assertJson([
                 'success' => true
             ])
             ->assertJsonStructure([
                 'message',
                 'order'
             ]);

    $order->refresh();
    expect($order->status)->toBe('diproses');
});

it('fails validation in updateStatus', function () {
    $response = $this->postJson('/api/update-status', [
        'order_id' => 'invalid',
        'status' => ''
    ]);

    $response->assertStatus(422)
             ->assertJson([
                 'success' => false,
                 'message' => 'Validasi gagal'
             ]);
});

// Tests for getStatusOptions method
it('returns status options successfully', function () {
    $response = $this->getJson('/api/status-options');

    $response->assertStatus(200)
             ->assertJson([
                 'success' => true,
                 'data' => [
                     'Diproses' => 'Diproses',
                     'Selesai' => 'Selesai',
                     'Dikirim' => 'Dikirim',
                     'Dibatalkan' => 'Dibatalkan',
                     'Pending' => 'Pending'
                 ]
             ]);
});

// Tests for bulkUpdateStatus method
it('bulk updates status successfully', function () {
    $orders = Order::factory()->count(3)->create(['status' => 'diproses']);
    $orderIds = $orders->pluck('id')->toArray();

    $response = $this->postJson('/api/bulk-update-status', [
        'order_ids' => $orderIds,
        'status' => 'antar'
    ]);

    $response->assertStatus(200)
             ->assertJson([
                 'success' => true,
                 'message' => 'Berhasil mengupdate 3 order'
             ]);

    foreach ($orders as $order) {
        $order->refresh();
        expect($order->status)->toBe('antar');
    }
});

it('fails validation in bulkUpdateStatus', function () {
    $response = $this->postJson('/api/bulk-update-status', [
        'order_ids' => [],
        'status' => ''
    ]);

    $response->assertStatus(422)
             ->assertJson([
                 'success' => false,
                 'message' => 'Validasi gagal'
             ]);
});
