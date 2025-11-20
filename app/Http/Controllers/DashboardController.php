<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show dashboard based on user role
     */
    public function index(Request $request)
    {
        logger('DashboardController::index called, user: ' . (Auth::id() ?? 'null') . ', session: ' . json_encode(session()->all()));

        $user = Auth::user();
        $query = Order::query();

        // Role-based filtering
        if ($user && $user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // Search by customer name
        if ($request->filled('search')) {
            $query->where('customer_name', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter pengantaran - INI YANG HARUS DIPERBAIKI
        if ($request->has('delivery_type') && $request->delivery_type != '') {
            $query->where('delivery_type', $request->delivery_type);
        }

        // Final query with ordering and pagination
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        // Count orders per status
        $statusCounts = $this->getStatusCounts($user);

        // Return view based on role
        if ($user && $user->role === 'admin') {
            return view('dashboard.index', compact('orders', 'statusCounts'));
        }

        return view('dashboard.index', compact('orders', 'statusCounts'));
    }

    /**
     * Get status counts that match the view requirements
     */
    private function getStatusCounts($user)
    {
        $baseQuery = Order::query()
            ->when($user && $user->role !== 'admin', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        return [
            'diproses' => (clone $baseQuery)->where('status', 'diproses')->count(),
            'siap_antar' => (clone $baseQuery)->where('status', 'siap_antar')->count(),
            'antar' => (clone $baseQuery)->where('status', 'antar')->count(),
            'sampai_tujuan' => (clone $baseQuery)->where('status', 'sampai_tujuan')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
            'total' => $baseQuery->count(),
        ];
    }

    /**
     * Show sales report for admin
     */
    public function report(Request $request)
    {
        // Only allow admin access
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Get period filter (default to monthly)
        $period = $request->get('period', 'monthly');

        // Base query for all orders
        $baseQuery = Order::query();

        // Aggregate data based on period
        $salesData = [];
        $totalRevenue = 0;
        $totalTransactions = 0;

        switch ($period) {
            case 'daily':
                $salesData = $baseQuery
                    ->selectRaw('DATE(created_at) as period, COUNT(*) as transactions, SUM(total_price) as revenue')
                    ->groupBy('period')
                    ->orderBy('period', 'desc')
                    ->get()
                    ->map(function ($item) use (&$totalRevenue, &$totalTransactions) {
                        $totalRevenue += $item->revenue;
                        $totalTransactions += $item->transactions;
                        return [
                            'period' => Carbon::parse($item->period)->format('d M Y'),
                            'transactions' => $item->transactions,
                            'revenue' => $item->revenue
                        ];
                    });
                break;

            case 'weekly':
                $salesData = $baseQuery
                    ->selectRaw('YEAR(created_at) as year, WEEK(created_at) as week, COUNT(*) as transactions, SUM(total_price) as revenue')
                    ->groupBy('year', 'week')
                    ->orderBy('year', 'desc')
                    ->orderBy('week', 'desc')
                    ->get()
                    ->map(function ($item) use (&$totalRevenue, &$totalTransactions) {
                        $totalRevenue += $item->revenue;
                        $totalTransactions += $item->transactions;
                        return [
                            'period' => 'Week ' . $item->week . ' ' . $item->year,
                            'transactions' => $item->transactions,
                            'revenue' => $item->revenue
                        ];
                    });
                break;

            case 'monthly':
            default:
                $salesData = $baseQuery
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as transactions, SUM(total_price) as revenue')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->get()
                    ->map(function ($item) use (&$totalRevenue, &$totalTransactions) {
                        $totalRevenue += $item->revenue;
                        $totalTransactions += $item->transactions;
                        $monthName = Carbon::create()->month($item->month)->format('M');
                        return [
                            'period' => $monthName . ' ' . $item->year,
                            'transactions' => $item->transactions,
                            'revenue' => $item->revenue
                        ];
                    });
                break;
        }

        return view('dashboard.report', compact('salesData', 'totalRevenue', 'totalTransactions', 'period'));
    }
}