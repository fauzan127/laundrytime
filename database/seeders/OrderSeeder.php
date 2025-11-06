<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceType;
use App\Models\ClothingType;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // ========================================
        // SERVICE TYPES - REGULER (5 service)
        // ========================================
        ServiceType::create([
            'name' => 'Cuci Setrika 1 Hari',
            'type' => 'reguler',
            'description' => 'Layanan cuci setrika dikerjakan 1 hari',
            'price_per_kg' => 6000,
            'is_active' => true
        ]);

        ServiceType::create([
            'name' => 'Cuci Lipat 1 Hari',
            'type' => 'reguler',
            'description' => 'Layanan cuci lipat dikerjakan 1 hari',
            'price_per_kg' => 4000,
            'is_active' => true
        ]);

        ServiceType::create([
            'name' => 'Cuci Lipat 2 hari',
            'type' => 'reguler',
            'description' => 'Layanan uci lipat dikerjakan 1 hari',
            'price_per_kg' => 3500,
            'is_active' => true
        ]);

        ServiceType::create([
            'name' => 'Setrika 1 Hari',
            'type' => 'reguler',
            'description' => 'Layanan setrika dikerjakan 1 hari',
            'price_per_kg' => 4000,
            'is_active' => true
        ]);

        ServiceType::create([
            'name' => 'Setrika 2 Hari',
            'type' => 'reguler',
            'description' => 'Layanan setrika dikerjakan 2 hari',
            'price_per_kg' => 3500,
            'is_active' => true
        ]);

        // ========================================
        // SERVICE TYPES - EXPRESS (4 service)
        // ========================================
        ServiceType::create([
            'name' => 'Cuci Setrika 4 Jam',
            'type' => 'express',
            'description' => 'Cuci setrika express (selesai 4 jam)',
            'price_per_kg' => 11000,
            'is_active' => true
        ]);

        ServiceType::create([
            'name' => 'Cuci Setrika 6 Jam',
            'type' => 'express',
            'description' => 'Cuci setrika express (selesai 6 jam)',
            'price_per_kg' => 8000,
            'is_active' => true
        ]);

        ServiceType::create([
            'name' => 'Cuci Lipat 2 Jam',
            'type' => 'express',
            'description' => 'Cuci lipat express (selesai 2 jam)',
            'price_per_kg' => 8000,
            'is_active' => true
        ]);

        ServiceType::create([
            'name' => 'Setrika 2 Jam',
            'type' => 'express',
            'description' => 'Setrika sexpress (Selesai 2 jam)',
            'price_per_kg' => 8000,
            'is_active' => true
        ]);

        // ========================================
        // CLOTHING TYPES (tetap sama)
        // ========================================
        $clothingItems = [
            ['name' => 'Bedcover 2kg+', 'price' => 25000],
            ['name' => 'Bedcover 3kg+', 'price' => 30000],
            ['name' => 'Sprei Set', 'price' => 15000],
            ['name' => 'Sprei tanpa bantal', 'price' => 10000],
            ['name' => 'Selimut Bulu besar', 'price' => 15000],
            ['name' => 'Selimut bulu sedang', 'price' => 10000],
            ['name' => 'Boneka besar', 'price' => 20000],
            ['name' => 'Boneka sedang', 'price' => 15000],
            ['name' => 'Boneka kecil', 'price' => 10000],
            ['name' => 'Seragam', 'price' => 15000],
            ['name' => 'Dress', 'price' => 15000],
            ['name' => 'Jas', 'price' => 20000],
            ['name' => 'Handuk besar', 'price' => 10000],
            ['name' => 'Handuk sedang', 'price' => 8000],
            ['name' => 'Handuk kecil', 'price' => 5000],
            ['name' => 'Sepatu non-putih', 'price' => 25000],
            ['name' => 'Sepatu putih', 'price' => 30000],
            ['name' => 'Sepatu kulit', 'price' => 25000],
        ];

        foreach ($clothingItems as $item) {
            ClothingType::create([
                'name' => $item['name'],
                'description' => null,
                'additional_price' => $item['price'],
                'is_active' => true
            ]);
        }
    }
}