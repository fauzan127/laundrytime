<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\GoogleController;
use Laravel\Socialite\Two\User as SocialiteUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('google redirect redirects to Google OAuth', function () {
    Socialite::shouldReceive('driver->redirect')->andReturn(redirect('https://google.com/oauth'));

    $response = $this->get(route('google.login'));

    $response->assertRedirect('https://google.com/oauth');
});

test('handleGoogleCallback creates or updates user and logs in', function () {
    // Mock redirector bawaan Laravel
    $redirectResponse = Mockery::mock(\Illuminate\Http\RedirectResponse::class);
    $redirector = Mockery::mock(\Illuminate\Routing\Redirector::class);
    $redirector->shouldReceive('route')->with('dashboard')->andReturn($redirectResponse);
    app()->instance('redirect', $redirector);

    // Mock data user dari Google
    $googleUser = Mockery::mock(SocialiteUser::class);
    $googleUser->shouldReceive('getEmail')->andReturn('test@example.com');
    $googleUser->shouldReceive('getName')->andReturn('Test User');
    $googleUser->shouldReceive('getId')->andReturn('123456');

    // Mock Socialite
    Socialite::shouldReceive('driver')->with('google')->andReturnSelf();
    Socialite::shouldReceive('user')->andReturn($googleUser);

    // Mock User model (tanpa DB)
    $user = Mockery::mock(User::class)->makePartial();
    User::shouldReceive('updateOrCreate')
        ->once()
        ->with(
            ['email' => 'test@example.com'],
            Mockery::on(function ($data) {
                return $data['name'] === 'Test User'
                    && password_verify('123456', $data['password']);
            })
        )
        ->andReturn($user);

    // Mock Auth
    Auth::shouldReceive('login')->with($user)->once();

    // Jalankan controller
    $controller = new GoogleController();
    $response = $controller->handleGoogleCallback();

    expect($response)->toBe($redirectResponse);
});

test('google callback handles exception and redirects to login', function () {
    Socialite::shouldReceive('driver->user')->andThrow(new \Exception('OAuth error'));

    $response = $this->get('auth/google/callback');

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('error', 'Login Google gagal, coba lagi.');
});
