<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'adminku@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '08528374478',
            'address' => 'Jalan Jalan',
            'email_verified_at' => now(),

        ]);
        User::create([
        'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password345'),
            'role' => 'user',
            'phone' => '089786756437',
            'address' => 'Jalan Jalan',
            'email_verified_at' => now(),
        ]);
    }
}
