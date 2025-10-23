<?php

namespace Database\Factories;

use App\Models\ServiceType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceTypeFactory extends Factory
{
    protected $model = ServiceType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['Cuci Kering', 'Cuci Setrika', 'Setrika Saja', 'Dry Clean']),
            'description' => $this->faker->sentence(),
            'price_per_kg' => $this->faker->numberBetween(5000, 15000),
            'is_active' => true,
        ];
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }
}