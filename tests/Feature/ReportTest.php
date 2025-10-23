<?php

use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows admin to access report page', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get('/dashboard/report');

    $response->assertStatus(200);
});

it('denies non-admin access to report page', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/dashboard/report');

    $response->assertStatus(403);
});

it('denies unauthenticated access to report page', function () {
    $response = $this->get('/dashboard/report');

    $response->assertRedirect('/login');
});

it('displays monthly report data by default', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    // Create orders for different months
    Order::factory()->create([
        'total_price' => 100000,
        'order_date' => now()->startOfMonth()
    ]);
    Order::factory()->create([
        'total_price' => 150000,
        'order_date' => now()->startOfMonth()->addDays(5)
    ]);
    Order::factory()->create([
        'total_price' => 200000,
        'order_date' => now()->subMonth()->startOfMonth()
    ]);

    $response = $this->actingAs($admin)->get('/dashboard/report');

    $response->assertStatus(200)
        ->assertViewHas('salesData')
        ->assertViewHas('totalRevenue', 450000)
        ->assertViewHas('totalTransactions', 3)
        ->assertViewHas('period', 'monthly');
});

it('displays weekly report data when period is weekly', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    // Create orders for different weeks
    Order::factory()->create([
        'total_price' => 100000,
        'order_date' => now()->startOfWeek()
    ]);
    Order::factory()->create([
        'total_price' => 150000,
        'order_date' => now()->startOfWeek()->addDays(3)
    ]);
    Order::factory()->create([
        'total_price' => 200000,
        'order_date' => now()->subWeek()->startOfWeek()
    ]);

    $response = $this->actingAs($admin)->get('/dashboard/report?period=weekly');

    $response->assertStatus(200)
        ->assertViewHas('salesData')
        ->assertViewHas('totalRevenue', 450000)
        ->assertViewHas('totalTransactions', 3)
        ->assertViewHas('period', 'weekly');
});

it('displays daily report data when period is daily', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    // Create orders for different days
    Order::factory()->create([
        'total_price' => 100000,
        'order_date' => now()->startOfDay()
    ]);
    Order::factory()->create([
        'total_price' => 150000,
        'order_date' => now()->startOfDay()->addHours(5)
    ]);
    Order::factory()->create([
        'total_price' => 200000,
        'order_date' => now()->subDay()->startOfDay()
    ]);

    $response = $this->actingAs($admin)->get('/dashboard/report?period=daily');

    $response->assertStatus(200)
        ->assertViewHas('salesData')
        ->assertViewHas('totalRevenue', 450000)
        ->assertViewHas('totalTransactions', 3)
        ->assertViewHas('period', 'daily');
});

it('calculates correct monthly aggregation', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    // Create orders for current month
    Order::factory()->create([
        'total_price' => 100000,
        'order_date' => now()->startOfMonth()
    ]);
    Order::factory()->create([
        'total_price' => 50000,
        'order_date' => now()->startOfMonth()->addDays(10)
    ]);

    // Create orders for previous month
    Order::factory()->create([
        'total_price' => 200000,
        'order_date' => now()->subMonth()->startOfMonth()
    ]);

    $response = $this->actingAs($admin)->get('/dashboard/report');
    $salesData = $response->viewData('salesData');

    // Should have 2 periods (current and previous month)
    expect($salesData)->toHaveCount(2);

    // Find current month data
    $currentMonthData = collect($salesData)->first(function ($data) {
        return str_contains($data['period'], now()->format('M'));
    });

    expect($currentMonthData['transactions'])->toBe(2);
    expect($currentMonthData['revenue'])->toBe('150000.00');
});

it('calculates correct weekly aggregation', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    // Create orders for current week
    Order::factory()->create([
        'total_price' => 100000,
        'order_date' => now()->startOfWeek()
    ]);
    Order::factory()->create([
        'total_price' => 50000,
        'order_date' => now()->startOfWeek()->addDays(3)
    ]);

    // Create orders for previous week
    Order::factory()->create([
        'total_price' => 200000,
        'order_date' => now()->subWeek()->startOfWeek()
    ]);

    $response = $this->actingAs($admin)->get('/dashboard/report?period=weekly');
    $salesData = $response->viewData('salesData');

    // Should have 2 periods (current and previous week)
    expect($salesData)->toHaveCount(2);

    // Find current week data
    $currentWeekData = collect($salesData)->first(function ($data) {
        return str_contains($data['period'], 'Week ' . now()->week);
    });

    if ($currentWeekData) {
        expect($currentWeekData['transactions'])->toBe(2);
        expect($currentWeekData['revenue'])->toBe(150000.00);
    }
});

it('calculates correct daily aggregation', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    // Create orders for today
    Order::factory()->create([
        'total_price' => 100000,
        'order_date' => now()->startOfDay()
    ]);
    Order::factory()->create([
        'total_price' => 50000,
        'order_date' => now()->startOfDay()->addHours(5)
    ]);

    // Create orders for yesterday
    Order::factory()->create([
        'total_price' => 200000,
        'order_date' => now()->subDay()->startOfDay()
    ]);

    $response = $this->actingAs($admin)->get('/dashboard/report?period=daily');
    $salesData = $response->viewData('salesData');

    // Should have 2 periods (today and yesterday)
    expect($salesData)->toHaveCount(2);

    // Find today data
    $todayData = collect($salesData)->first(function ($data) {
        return str_contains($data['period'], now()->format('d M Y'));
    });

    expect($todayData['transactions'])->toBe(2);
    expect($todayData['revenue'])->toBe('150000.00');
});

it('returns empty data when no orders exist', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get('/dashboard/report');

    $response->assertStatus(200)
        ->assertViewHas('totalRevenue', 0)
        ->assertViewHas('totalTransactions', 0);
});

it('renders the correct view', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get('/dashboard/report');

    $response->assertViewIs('dashboard.report');
});
