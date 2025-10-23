<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function index()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $user = Auth::user();

        // Dummy order list
        $orders = collect([
            (object)[
                'id' => 1,
                'order_date' => now()->subDays(3),
                'total' => 10000,
                'status' => 'Belum Dibayar'
            ],
            (object)[
                'id' => 2,
                'order_date' => now()->subDays(2),
                'total' => 15000,
                'status' => 'Sudah Dibayar'
            ]
        ]);

        $snapTokens = [];

        foreach ($orders as $order) {
            if ($order->status === 'Belum Dibayar') {
                $params = [
                    'transaction_details' => [
                        'order_id' => 'ORDER-' . $order->id . '-' . uniqid(), // ✅ Unik agar tidak error 400
                        'gross_amount' => $order->total,
                    ],
                    'customer_details' => [
                        'first_name' => $user->name,
                        'email' => $user->email,
                    ],
                    'item_details' => [
                        [
                            'id' => 'order-' . $order->id,
                            'price' => $order->total,
                            'quantity' => 1,
                            'name' => 'Pembayaran Order #' . $order->id,
                        ]
                    ],
                ];

                $snapTokens[$order->id] = Snap::getSnapToken($params);
            }
        }

        return view('payment.payment', compact('orders', 'snapTokens'));
    }
}
