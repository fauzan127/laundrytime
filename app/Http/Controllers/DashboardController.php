<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
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

        // Final query with ordering and pagination
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        // FIX: Count orders per status yang sesuai dengan view
        $statusCounts = $this->getStatusCounts($user);

        // Return view based on role
        if ($user && $user->role === 'admin') {
            return view('dashboard.admin', compact('orders', 'statusCounts'));
        }

        return view('dashboard.user', compact('orders', 'statusCounts'));
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
            'sampai_tujuan' => (clone $baseQuery)->where('status', 'sampai_tujuan')->count(),
            'total' => $baseQuery->count(), // total semua pesanan
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
                    ->selectRaw('DATE(order_date) as period, COUNT(*) as transactions, SUM(total_price) as revenue')
                    ->groupBy('period')
                    ->orderBy('period', 'desc')
                    ->get()
                    ->map(function ($item) use (&$totalRevenue, &$totalTransactions) {
                        $totalRevenue += $item->revenue;
                        $totalTransactions += $item->transactions;
                        return [
                            'period' => \Carbon\Carbon::parse($item->period)->format('d M Y'),
                            'transactions' => $item->transactions,
                            'revenue' => $item->revenue
                        ];
                    });
                break;

            case 'weekly':
                $salesData = $baseQuery
                    ->selectRaw('YEAR(order_date) as year, WEEK(order_date) as week, COUNT(*) as transactions, SUM(total_price) as revenue')
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
                    ->selectRaw('YEAR(order_date) as year, MONTH(order_date) as month, COUNT(*) as transactions, SUM(total_price) as revenue')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->get()
                    ->map(function ($item) use (&$totalRevenue, &$totalTransactions) {
                        $totalRevenue += $item->revenue;
                        $totalTransactions += $item->transactions;
                        $monthName = \Carbon\Carbon::create()->month($item->month)->format('M');
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
