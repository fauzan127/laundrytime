<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function index()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil semua order milik user
        $orders = Order::with('payment')->where('user_id', $user->id)->get();

        $snapTokens = [];

        foreach ($orders as $order) {
            // Cek apakah belum dibayar
            if (!$order->payment || $order->payment->payment_status === 'Belum Dibayar') {
                $orderId = 'ORDER-' . $order->id . '-' . uniqid();

                $params = [
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => (int) $order->total_price,
                    ],
                    'customer_details' => [
                        'first_name' => $user->name,
                        'email' => $user->email,
                    ],
                    'item_details' => [
                        [
                            'id' => 'order-' . $order->id,
                            'price' => (int) $order->total_price,
                            'quantity' => 1,
                            'name' => 'Pembayaran Order #' . $order->id,
                        ]
                    ],
                ];

                try {
                    $snapTokens[$order->id] = Snap::getSnapToken($params);
                } catch (\Exception $e) {
                    Log::error('Gagal membuat Snap token untuk order ' . $order->id . ': ' . $e->getMessage());
                    $snapTokens[$order->id] = null;
                }
            }
        }

        return view('payment.payment', compact('orders', 'snapTokens'));
    }
}
