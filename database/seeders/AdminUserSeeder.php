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
        User::updateOrCreate([
            'email' => 'adminku@gmail.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
        User::updateOrCreate([
            'email' => 'admin2@gmail.com',
        ], [
            'name' => 'Admin2',
            'password' => Hash::make('password345'),
            'role' => 'admin',
        ]);
    }
}
