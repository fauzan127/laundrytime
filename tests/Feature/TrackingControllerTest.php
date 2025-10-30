<?php

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('index route returns tracking view', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    Order::factory()->create(['status' => 'diproses']);

    $response = $this->get('/dashboard/tracking');

    $response->assertStatus(200);
    $response->assertViewIs('dashboard.tracking');
});

test('updateStatus route updates status successfully', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $order = Order::factory()->create(['status' => 'diproses']);

    $response = $this->put("/dashboard/tracking/{$order->id}/status", [
        'status' => 'siap_antar'
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Status berhasil diperbarui'
    ]);

    $order->refresh();
    expect($order->status)->toBe('siap_antar');
});

test('updateStatus route fails with invalid data', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $order = Order::factory()->create(['status' => 'diproses']);

    $response = $this->put("/dashboard/tracking/{$order->id}/status", [
        'status' => ''
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => false,
        'message' => 'Validasi gagal'
    ]);
});

test('show route returns detail tracking view', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    Order::factory()->create(['customer_name' => 'John Doe']);

    $response = $this->get('/dashboard/detailtracking/John%20Doe');

    $response->assertStatus(200);
    $response->assertViewIs('dashboard.detailtracking');
});
