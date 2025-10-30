<?php

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('login request rules are correct', function () {
    $request = new LoginRequest();

    $rules = $request->rules();

    expect($rules)->toHaveKey('email');
    expect($rules)->toHaveKey('password');
    expect($rules['email'])->toContain('required', 'string', 'email');
    expect($rules['password'])->toContain('required', 'string');
});

test('login request authorize returns true', function () {
    $request = new LoginRequest();

    $authorized = $request->authorize();

    expect($authorized)->toBeTrue();
});

test('throttle key is generated correctly', function () {
    $request = new LoginRequest();
    $request->merge([
        'email' => 'test@example.com',
    ]);

    // Mock IP
    $request->server->set('REMOTE_ADDR', '127.0.0.1');

    $throttleKey = $request->throttleKey();

    expect($throttleKey)->toBe('test@example.com|127.0.0.1');
});
