<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'customer_name' => $this->faker->name(),
            'customer_phone' => '08123456789',
            'status' => 'Diproses',
            'delivery_type' => 'antar_jemput',
            'address' => 'Test Address',
            'pickup_date' => '2023-01-01',
            'pickup_time' => '10:00',
            'weight' => 5.0,
            'total_price' => 100000.0,
            'notes' => 'Test notes',
            'order_date' => now(),
            'user_id' => User::factory(),
        ];
    }

    public function pending()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'diproses',
        ]);
    }
}