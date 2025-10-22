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
            'antar' => (clone $baseQuery)->where('status', 'antar')->count(),
            'sampai_tujuan' => (clone $baseQuery)->where('status', 'sampai_tujuan')->count(),
        ];
    }
}