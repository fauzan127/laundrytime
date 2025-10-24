<?php

use App\Http\Controllers\ProfileController;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewInstance;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mockery::close();
    }

    public function test_index_displays_user_profile()
{
    $user = Mockery::mock(User::class);
    $request = Mockery::mock(Request::class);
    $request->shouldReceive('user')->andReturn($user);

    // Mock instance view factory yang dipakai helper view()
    $viewFactory = Mockery::mock(\Illuminate\View\Factory::class);
    $viewInstance = Mockery::mock(\Illuminate\View\View::class);

    $viewFactory->shouldReceive('make')
        ->with('profile.index', ['user' => $user], [])
        ->andReturn($viewInstance);

    // Ganti binding 'view' di container Laravel dengan mock ini
    $this->app->instance('view', $viewFactory);

    $controller = new \App\Http\Controllers\ProfileController();
    $response = $controller->index($request);

    $this->assertEquals($viewInstance, $response);
}

public function test_edit_displays_user_profile_edit_form()
{
    $user = Mockery::mock(User::class);
    $request = Mockery::mock(Request::class);
    $request->shouldReceive('user')->andReturn($user);

    $viewFactory = Mockery::mock(\Illuminate\View\Factory::class);
    $viewInstance = Mockery::mock(\Illuminate\View\View::class);

    $viewFactory->shouldReceive('make')
        ->with('profile.edit', ['user' => $user], [])
        ->andReturn($viewInstance);

    $this->app->instance('view', $viewFactory);

    $controller = new \App\Http\Controllers\ProfileController();
    $response = $controller->edit($request);

    $this->assertEquals($viewInstance, $response);
}

}
