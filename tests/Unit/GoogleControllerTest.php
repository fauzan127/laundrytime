<?php

use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use App\Models\User;
use Tests\TestCase;

class GoogleControllerTest extends TestCase
{
    protected $userMock;

    protected function setUp(): void
    {
        parent::setUp();
        // pastikan tidak bentrok antara unit test satu dengan lain
        Mockery::close();
    }

    public function test_redirectToGoogle_redirects_to_Google_OAuth()
    {
        Socialite::shouldReceive('driver')->with('google')->andReturnSelf();
        Socialite::shouldReceive('redirect')->andReturn('redirect response');

        $controller = new GoogleController();
        $response = $controller->redirectToGoogle();

        $this->assertEquals('redirect response', $response);
    }

    public function test_handleGoogleCallback_creates_or_updates_user_and_logs_in()
    {
        // Mock data dari Google
        $googleUser = Mockery::mock(SocialiteUser::class);
        $googleUser->shouldReceive('getEmail')->andReturn('test@example.com');
        $googleUser->shouldReceive('getName')->andReturn('Test User');
        $googleUser->shouldReceive('getId')->andReturn('123456');

        // Mock Socialite
        Socialite::shouldReceive('driver')->with('google')->andReturnSelf();
        Socialite::shouldReceive('user')->andReturn($googleUser);

        // Mock Hash facade
        Hash::shouldReceive('make')->with('123456')->andReturn('$2y$10$hashedpassword');

        // Mock User model static method
        $userMock = Mockery::mock('alias:App\Models\User');
        $userMock->shouldReceive('updateOrCreate')
            ->once()
            ->with(
                ['email' => 'test@example.com'],
                [
                    'name' => 'Test User',
                    'password' => '$2y$10$hashedpassword'
                ]
            )
            ->andReturn($userMock);

        // Mock Auth
        Auth::shouldReceive('login')->with($userMock)->once();

        // Mock redirect
        $redirectResponseDashboard = Mockery::mock(\Illuminate\Http\RedirectResponse::class);
        $redirectResponseLogin = Mockery::mock(\Illuminate\Http\RedirectResponse::class);
        $redirectResponseLogin->shouldReceive('with')->andReturnSelf();
        Redirect::shouldReceive('route')->with('dashboard')->andReturn($redirectResponseDashboard);
        Redirect::shouldReceive('route')->with('login')->andReturn($redirectResponseLogin);

        // Jalankan controller
        $controller = new GoogleController();
        $response = $controller->handleGoogleCallback();

        // Pastikan redirect ke dashboard
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    public function test_handleGoogleCallback_handles_exception_and_redirects_to_login()
    {
        $redirector = Mockery::mock(\Illuminate\Routing\Redirector::class);
        $redirectResponseLogin = Mockery::mock(\Illuminate\Http\RedirectResponse::class);
        $redirector->shouldReceive('route')->with('login')->andReturn($redirectResponseLogin);
        $redirectResponseLogin->shouldReceive('with')
            ->with('error', 'Login Google gagal, coba lagi.')
            ->andReturn($redirectResponseLogin);

        app()->instance('redirect', $redirector);

        // Mock Socialite agar melempar error
        Socialite::shouldReceive('driver')->with('google')->andReturnSelf();
        Socialite::shouldReceive('user')->andThrow(new \Exception('OAuth error'));

        $controller = new GoogleController();
        $response = $controller->handleGoogleCallback();

        $this->assertEquals($redirectResponseLogin, $response);
    }
}
