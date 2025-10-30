<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Payment;
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

        // Ambil order milik user dengan pagination
        $orders = Order::with('payment')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $snapTokens = [];

        foreach ($orders as $order) {
            $payment = $order->payment;

            if (!$payment || in_array($payment->payment_status, ['Belum Dibayar', 'Menunggu Pembayaran'])) {
                if ($payment && $payment->token) {
                    $snapTokens[$order->id] = $payment->token;
                    continue;
                }

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
                    'enabled_payments' => [
                        'credit_card',
                        'gopay',
                        'qris',
                        'bank_transfer',
                        'shopeepay',
                        'permata_va',
                        'bca_va',
                        'bni_va',
                        'other_va',
                    ],
                ];

                try {
                    $snapToken = Snap::getSnapToken($params);
                    $snapTokens[$order->id] = $snapToken;

                    if (!$payment) {
                        $payment = new Payment();
                        $payment->order_id = $order->id;
                        $payment->payment_status = 'Belum Dibayar';
                    }

                    $payment->token = $snapToken;
                    $payment->save();
                } catch (\Exception $e) {
                    Log::error('Gagal membuat Snap token untuk order ' . $order->id . ': ' . $e->getMessage());
                    $snapTokens[$order->id] = null;
                }
            }
        }

        return view('payment.payment', compact('orders', 'snapTokens'));
    }
}