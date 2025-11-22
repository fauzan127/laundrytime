<?php

namespace Tests\Feature;

use Tests\TestCase;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_to_google()
    {
        // Test if the redirect to Google OAuth URL works
        $response = $this->get(route('google.login'));
        $response->assertRedirect();
        $redirectUrl = $response->headers->get('Location');
        $this->assertStringContainsString('accounts.google.com', $redirectUrl);
    }

    public function test_handle_google_callback_existing_user()
    {
        $googleUser = new class {
            public function getId()
            {
                return '1234567890';
            }
            public function getEmail()
            {
                return 'existing@example.com';
            }
            public function getName()
            {
                return 'Existing User';
            }
        };

        // Create existing user with google_id
        $user = User::factory()->create([
            'google_id' => '1234567890',
            'email' => 'existing@example.com',
        ]);

        Socialite::shouldReceive('driver->user')
            ->andReturn($googleUser);

        $response = $this->get(route('google.callback'));
        $response->assertRedirect(route('dashboard.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_handle_google_callback_new_user_creation()
    {
        $googleUser = new class {
            public function getId()
            {
                return '0987654321';
            }
            public function getEmail()
            {
                return 'newuser@example.com';
            }
            public function getName()
            {
                return 'New User';
            }
        };

        Socialite::shouldReceive('driver->user')
            ->andReturn($googleUser);

        $response = $this->get(route('google.callback'));
        $response->assertRedirect(route('dashboard.index'));

        $user = User::where('google_id', '0987654321')->first();
        $this->assertNotNull($user);
        $this->assertAuthenticatedAs($user);
    }
}
