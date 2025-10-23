<?php

namespace Database\Factories;

use App\Models\ClothingType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClothingTypeFactory extends Factory
{
    protected $model = ClothingType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['Pakaian Biasa', 'Jaket', 'Selimut', 'Bed Cover']),
            'description' => $this->faker->sentence(),
            'additional_price' => $this->faker->numberBetween(1000, 5000),
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