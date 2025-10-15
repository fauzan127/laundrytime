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

            
        ]);
        User::create([
        'name' => 'Admin2',
            'email' => 'admin2@gmail.com',
            'password' => Hash::make('password345'),
            'role' => 'admin',
        ]);
    }
}
