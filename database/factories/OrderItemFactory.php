<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\ServiceType;
use App\Models\ClothingType;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition()
    {
        $weight = $this->faker->randomFloat(1, 1, 10);
        $servicePrice = $this->faker->numberBetween(5000, 15000);
        $additionalPrice = $this->faker->numberBetween(1000, 5000);
        
        return [
            'order_id' => Order::factory(),
            'service_type_id' => ServiceType::factory(),
            'clothing_type_id' => ClothingType::factory(),
            'weight' => $weight,
            'price' => ($servicePrice + $additionalPrice) * $weight,
        ];
    }
}