<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('profile index displays user profile', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('profile.index'));

    $response->assertStatus(200);
    $response->assertViewIs('profile.index');
    $response->assertViewHas('user', $user);
});

test('profile edit displays user profile edit form', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('profile.edit'));

    $response->assertStatus(200);
    $response->assertViewIs('profile.edit');
    $response->assertViewHas('user', $user);
});

test('profile update updates user profile and redirects', function () {
    $user = User::factory()->create();

    $data = [
        'name' => 'Updated Name',
        'email' => 'new@example.com',
        'phone' => '081234567890',
        'address' => 'Updated Address',
    ];

    $response = $this->actingAs($user)->patch(route('profile.update'), $data);

    $response->assertRedirect(route('profile.edit'));
    $response->assertSessionHas('status', 'profile-updated');

    $user->refresh();
    expect($user->name)->toBe('Updated Name');
    expect($user->email)->toBe('new@example.com');
    expect($user->phone)->toBe('081234567890');
    expect($user->address)->toBe('Updated Address');
});

test('profile destroy deletes user account and logs out', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->delete(route('profile.destroy'), [
        'password' => 'password',
    ]);

    $response->assertRedirect('/login');

    $this->assertGuest();
    expect(User::find($user->id))->toBeNull();
});
