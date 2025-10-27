<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(); 
        // Buat user dummy dan login otomatis untuk semua test
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function user_can_view_order_list()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        \App\Models\Order::factory()->count(3)->create();

        $response = $this->get('/orders');
        $response->assertStatus(200);
        $response->assertSee('Daftar Pesanan'); // sesuaikan dengan teks di blade kamu
    }

    /** @test */
    public function user_can_create_an_order()
    {
        $data = [
            'customer_name' => 'Hana Mardhatillah',
            'phone' => '08123456789',
            'service_type_id' => 1,
            'clothing_type_id' => 2,
            'weight' => 3,
            'payment_status' => 'unpaid',
            'transaction_date' => now(),
        ];

        $response = $this->post('/orders', $data);
        $response->assertStatus(302); // redirect sukses

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Hana Mardhatillah',
        ]);
    }

    /** @test */
    public function user_can_view_order_detail()
    {
        $order = Order::factory()->create([
            'customer_name' => 'Pelanggan Tes',
        ]);

        $response = $this->get('/orders/' . $order->id);
        $response->assertStatus(200);
        $response->assertSee('Pelanggan Tes');
    }

    /** @test */
    public function user_can_update_an_order()
    {
        $order = Order::factory()->create();

        $response = $this->put('/orders/' . $order->id, [
            'customer_name' => 'Update Nama',
            'phone' => '08123456789',
            'service_type_id' => 1,
            'clothing_type_id' => 2,
            'weight' => 5,
            'payment_status' => 'paid',
            'transaction_date' => now(),
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_name' => 'Update Nama',
        ]);
    }

    /** @test */
    public function user_can_delete_an_order()
    {
        $order = Order::factory()->create();

        $response = $this->delete('/orders/' . $order->id);
        $response->assertStatus(302);

        $this->assertDatabaseMissing('orders', [
            'id' => $order->id,
        ]);
    }
}
