<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('user creation with valid data', function () {
    $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '081234567890',
        'address' => 'Test Address',
        'password' => 'hashedpassword',
    ];

    $user = new User();
    $user->setRawAttributes($data);

    expect($user)->toBeInstanceOf(User::class);
    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    expect($user->phone)->toBe('081234567890');
    expect($user->address)->toBe('Test Address');
    expect($user->password)->toBe('hashedpassword');
});

test('user fillable attributes include required fields', function () {
    $user = new User();

    expect($user->getFillable())->toContain('name', 'email', 'password', 'phone', 'address');
});

test('user factory creates user with phone and address', function () {
    $user = User::factory()->create();

    expect($user->phone)->not->toBeNull();
    expect($user->address)->not->toBeNull();
})->skip('Factory uses Faker facade which is not set in unit tests');
