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
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $deliveryTypes = ['antar_jemput', 'pengantaran_pribadi'];

        return [
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'customer_name' => $this->faker->name(),
            'customer_phone' => $this->faker->phoneNumber(),
            'status' => $this->faker->randomElement($statuses),
            'delivery_type' => $this->faker->randomElement($deliveryTypes),
            'address' => $this->faker->address(),
            'pickup_date' => $this->faker->date(),
            'pickup_time' => $this->faker->time(),
            'weight' => $this->faker->randomFloat(2, 1, 10),
            'total_price' => $this->faker->randomFloat(2, 50000, 200000),
            'notes' => $this->faker->sentence(),
            'order_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'user_id' => User::factory(),
        ];
    }
}
