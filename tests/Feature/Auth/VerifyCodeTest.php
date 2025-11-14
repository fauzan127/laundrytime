<?php

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VerifyEmailWithCode;

test('verify code screen can be rendered', function () {
    $user = User::factory()->create([
        'verification_code' => '123456',
        'verification_code_expires_at' => now()->addMinutes(10),
    ]);

    $response = $this->actingAs($user)->get(route('verification.code'));

    $response->assertStatus(200);
});

test('email verification code can be verified', function () {
    $user = User::factory()->create([
        'verification_code' => '123456',
        'verification_code_expires_at' => now()->addMinutes(10),
        'email_verified_at' => null,
    ]);

    $response = $this->actingAs($user)->from(route('verification.code'))->post(route('verification.code.verify'), [
        'code' => '123456',
    ]);

    $response->assertRedirect(route('dashboard'));
    $user->refresh();
    expect($user->hasVerifiedEmail())->toBeTrue();
});

test('email verification code is invalid', function () {
    $user = User::factory()->create([
        'verification_code' => '123456',
        'verification_code_expires_at' => now()->addMinutes(10),
    ]);

    $response = $this->actingAs($user)->from(route('verification.code'))->post(route('verification.code.verify'), [
        'code' => '654321',
    ]);

    $response->assertRedirect(route('verification.code'));
    $response->assertSessionHasErrors('code');
});

test('email verification code is expired', function () {
    $user = User::factory()->create([
        'verification_code' => '123456',
        'verification_code_expires_at' => now()->subMinutes(10),
    ]);

    $response = $this->actingAs($user)->from(route('verification.code'))->post(route('verification.code.verify'), [
        'code' => '123456',
    ]);

    $response->assertRedirect(route('verification.code'));
    $response->assertSessionHasErrors('code');
});

test('resend verification code', function () {
    Notification::fake();

    $user = User::factory()->create([
        'verification_code' => '123456',
        'verification_code_expires_at' => now()->addMinutes(10),
        'email_verified_at' => null,
    ]);

    $response = $this->actingAs($user)->from(route('verification.code'))->post(route('verification.code.resend'));

    $response->assertRedirect(route('verification.code'));
    $response->assertSessionHas('status', 'verification-code-sent');

    Notification::assertSentTo($user, VerifyEmailWithCode::class);
});
