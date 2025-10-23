<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ServiceType;
use App\Models\ClothingType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('index displays orders for authenticated user', function () {
    // Arrange
    $user = User::factory()->create();
    $orders = Order::factory()->count(3)->create(['user_id' => $user->id]);

    // Act & Assert
    $response = $this->actingAs($user)->get(route('order.index'));

    $response->assertStatus(200);
    $response->assertViewHas('orders');
    $response->assertViewHas('orders', function ($viewOrders) {
        return $viewOrders->count() === 3;
    });
});

test('index redirects unauthenticated user', function () {
    // Act & Assert
    $response = $this->get(route('order.index'));

    $response->assertRedirect(route('login'));
});

test('create displays form for authenticated user', function () {
    // Arrange
    $user = User::factory()->create();
    ServiceType::factory()->count(2)->create(['is_active' => true]);
    ClothingType::factory()->count(2)->create(['is_active' => true]);

    // Act & Assert
    $response = $this->actingAs($user)->get(route('order.create'));

    $response->assertStatus(200);
    $response->assertViewHas(['serviceTypes', 'clothingTypes']);
});

test('create redirects unauthenticated user', function () {
    // Act & Assert
    $response = $this->get(route('order.create'));

    $response->assertRedirect(route('login'));
});

test('store creates order with valid data', function () {
    // Arrange
    $user = User::factory()->create();
    $serviceType = ServiceType::factory()->create(['price_per_kg' => 10000]);
    $clothingType = ClothingType::factory()->create(['additional_price' => 2000]);

    $data = [
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
    ];

    // Act & Assert
    $response = $this->actingAs($user)->post(route('order.store'), $data);

    $response->assertRedirect(route('order.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('orders', [
        'customer_name' => 'John Doe',
        'user_id' => $user->id,
    ]);
    $this->assertDatabaseHas('order_items', [
        'weight' => 2.5,
    ]);
});

test('store validates required fields', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    $response = $this->actingAs($user)->post(route('order.store'), []);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['customer_name', 'customer_phone', 'delivery_type', 'items']);
});

test('show displays order for authenticated user', function () {
    // Arrange
    $user = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $user->id]);
    OrderItem::factory()->count(2)->create(['order_id' => $order->id]);

    // Act & Assert
    $response = $this->actingAs($user)->get(route('order.show', $order));

    $response->assertStatus(200);
    $response->assertViewHas('order', $order);
    $response->assertViewHas('order', function ($viewOrder) {
        return $viewOrder->items->count() === 2;
    });
});

test('show returns 404 for non-existing order', function () {
    // Arrange
    $user = User::factory()->create();

    // Act & Assert
    $response = $this->actingAs($user)->get(route('order.show', 999));

    $response->assertStatus(404);
});

test('edit allows admin access', function () {
    // Arrange
    $admin = User::factory()->create(['role' => 'admin']);
    $order = Order::factory()->create();
    ServiceType::factory()->count(2)->create(['is_active' => true]);
    ClothingType::factory()->count(2)->create(['is_active' => true]);

    // Act & Assert
    $response = $this->actingAs($admin)->get(route('order.edit', $order));

    $response->assertStatus(200);
    $response->assertViewHas(['order', 'serviceTypes', 'clothingTypes']);
});

test('edit denies non-admin access', function () {
    // Arrange
    $user = User::factory()->create(['role' => 'user']);
    $order = Order::factory()->create();

    // Act & Assert
    $response = $this->actingAs($user)->get(route('order.edit', $order));

    $response->assertStatus(403);
});

test('update allows admin to update order', function () {
    // Arrange
    $admin = User::factory()->create(['role' => 'admin']);
    $order = Order::factory()->create();
    $serviceType = ServiceType::factory()->create(['price_per_kg' => 10000]);
    $clothingType = ClothingType::factory()->create(['additional_price' => 2000]);

    $data = [
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
    ];

    // Act & Assert
    $response = $this->actingAs($admin)->put(route('order.update', $order), $data);

    $response->assertRedirect(route('order.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'customer_name' => 'Updated Name',
        'status' => 'Proses',
    ]);
});

test('update denies non-admin access', function () {
    // Arrange
    $user = User::factory()->create(['role' => 'user']);
    $order = Order::factory()->create();

    // Act & Assert
    $response = $this->actingAs($user)->put(route('order.update', $order), []);

    $response->assertStatus(403);
});

test('destroy allows admin to delete order', function () {
    // Arrange
    $admin = User::factory()->create(['role' => 'admin']);
    $order = Order::factory()->create();
    OrderItem::factory()->count(2)->create(['order_id' => $order->id]);

    // Act & Assert
    $response = $this->actingAs($admin)->delete(route('order.destroy', $order));

    $response->assertRedirect(route('order.index'));
    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    $this->assertDatabaseMissing('order_items', ['order_id' => $order->id]);
});

test('destroy denies non-admin access', function () {
    // Arrange
    $user = User::factory()->create(['role' => 'user']);
    $order = Order::factory()->create();

    // Act & Assert
    $response = $this->actingAs($user)->delete(route('order.destroy', $order));

    $response->assertStatus(403);
    $this->assertDatabaseHas('orders', ['id' => $order->id]);
});
