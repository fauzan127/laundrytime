<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payment'])->latest();

        // Filter pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })->orWhere('id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        // Filter status pembayaran
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status == 'paid') {
                $query->whereHas('payment', function($q) {
                    $q->where('payment_status', '!=', 'Belum Dibayar');
                });
            } elseif ($request->status == 'unpaid') {
                $query->where(function($q) {
                    $q->whereHas('payment', function($paymentQuery) {
                        $paymentQuery->where('payment_status', 'Belum Dibayar');
                    })->orWhereDoesntHave('payment');
                });
            }
        }

        // Filter tanggal
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        $orders = $query->paginate(10);

        // Hitung statistik untuk dashboard
        $paidCount = Order::whereHas('payment', function($q) {
            $q->where('payment_status', '!=', 'Belum Dibayar');
        })->count();

        $unpaidCount = Order::where(function($q) {
            $q->whereHas('payment', function($paymentQuery) {
                $paymentQuery->where('payment_status', 'Belum Dibayar');
            })->orWhereDoesntHave('payment');
        })->count();

        $totalCount = Order::count();

        return view('payment.admin', compact('orders', 'paidCount', 'unpaidCount', 'totalCount'));
    }

    /**
     * Tandai sebagai sudah dibayar
     */
    public function markPaid($id)
    {
        DB::beginTransaction();
        try {
            $order = Order::findOrFail($id);
            
            // Cek apakah payment sudah ada
            $payment = $order->payment;
            
            if ($payment) {
                // Update payment yang sudah ada
                $payment->update([
                    'payment_status' => 'Sudah Dibayar',
                    'payment_method' => $payment->payment_method ?: 'Manual',
                    'updated_at' => now()
                ]);
            } else {
                // Buat payment baru
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'Manual',
                    'payment_status' => 'Sudah Dibayar',
                    'amount' => $order->total_price,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();
            
            return response()->json([
                'success' => true, 
                'message' => 'Status pembayaran berhasil diperbarui menjadi Sudah Dibayar'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tandai sebagai belum dibayar
     */
    public function markUnpaid($id)
    {
        DB::beginTransaction();
        try {
            $order = Order::findOrFail($id);
            
            if ($order->payment) {
                $order->payment->update([
                    'payment_status' => 'Belum Dibayar',
                    'updated_at' => now()
                ]);
            }

            DB::commit();
            
            return response()->json([
                'success' => true, 
                'message' => 'Status pembayaran berhasil diperbarui menjadi Belum Dibayar'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}