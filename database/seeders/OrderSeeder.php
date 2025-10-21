<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceType;
use App\Models\ClothingType;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Service Types
        ServiceType::create([
            'name' => 'Cuci Kering',
            'description' => 'Layanan cuci saja tanpa setrika',
            'price_per_kg' => 5000,
            'is_active' => true
        ]);

        ServiceType::create([
            'name' => 'Cuci Setrika',
            'description' => 'Layanan cuci lengkap dengan setrika',
            'price_per_kg' => 7000,
            'is_active' => true
        ]);

        ServiceType::create([
            'name' => 'Setrika Saja',
            'description' => 'Layanan setrika saja',
            'price_per_kg' => 4000,
            'is_active' => true
        ]);

        ServiceType::create([
            'name' => 'Express',
            'description' => 'Layanan cuci setrika express (selesai 1 hari)',
            'price_per_kg' => 10000,
            'is_active' => true
        ]);

        // Clothing Types
        ClothingType::create([
            'name' => 'Pakaian Reguler',
            'description' => 'Pakaian sehari-hari (baju, celana, dll)',
            'additional_price' => 0,
            'is_active' => true
        ]);

        ClothingType::create([
            'name' => 'Bedcover/Sprei',
            'description' => 'Bedcover dan sprei',
            'additional_price' => 3000,
            'is_active' => true
        ]);

        ClothingType::create([
            'name' => 'Selimut',
            'description' => 'Selimut tebal',
            'additional_price' => 2000,
            'is_active' => true
        ]);

        ClothingType::create([
            'name' => 'Karpet',
            'description' => 'Karpet dan permadani',
            'additional_price' => 5000,
            'is_active' => true
        ]);

        ClothingType::create([
            'name' => 'Boneka',
            'description' => 'Boneka dan mainan kain',
            'additional_price' => 2500,
            'is_active' => true
        ]);
    }
}