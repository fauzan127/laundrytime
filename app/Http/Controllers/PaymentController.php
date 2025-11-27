<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', true);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        Log::info('=== PAYMENT PROCESS START ===');
        Log::info('User: ' . $user->id . ' - ' . $user->name);

        $orders = Order::with('payment')
            ->where('user_id', $user->id)
            ->where('weight', '>', 0)
            ->latest()
            ->paginate(10);

        Log::info('Orders found: ' . count($orders->items()));

        $snapTokens = [];

        foreach ($orders as $order) {
            $payment = $order->payment;

            if ($order->weight <= 0) {
                Log::info('Order ' . $order->id . ' skipped: weight is 0 or null');
                continue;
            }

            $needsNewToken = false;
            $isFinalized = $payment && $payment->created_at->eq($payment->updated_at);

            if (!$payment) {
                $needsNewToken = true;
                Log::info('Order ' . $order->id . ': No payment record, need new token');
            } elseif ($isFinalized && in_array($payment->payment_status, ['Belum Dibayar', 'Menunggu Pembayaran'])) {
                // Finalized payment (weight set by admin) that is not paid - always generate new token to ensure correct price
                $needsNewToken = true;
                Log::info('Order ' . $order->id . ': Finalized unpaid payment, generating new token');
            } elseif (in_array($payment->payment_status, ['Belum Dibayar', 'Menunggu Pembayaran']) && $payment->token) {
                // Non-finalized payment with existing token - use it
                $snapTokens[$order->id] = $payment->token;
                Log::info('Using existing token for order: ' . $order->id);
                continue;
            } elseif (in_array($payment->payment_status, ['Belum Dibayar', 'Menunggu Pembayaran']) && !$payment->token) {
                // Non-finalized payment without token - generate new
                $needsNewToken = true;
                Log::info('Order ' . $order->id . ': Non-finalized payment without token, need new token');
            } else {
                Log::info('Order ' . $order->id . ': Payment status ' . ($payment ? $payment->payment_status : 'No Payment') . ', skipping token generation');
                continue;
            }

            if ($needsNewToken) {
                $orderId = 'ORDER-' . $order->id . '-' . uniqid();

                $params = [
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => (int) $order->total_price,
                    ],
                    'customer_details' => [
                        'first_name' => $user->name,
                        'email' => $user->email,
                        'phone' => $order->customer_phone ?? '081234567890',
                    ],
                    'item_details' => [
                        [
                            'id' => 'order-' . $order->id,
                            'price' => (int) $order->total_price,
                            'quantity' => 1,
                            'name' => 'Laundry Service - ' . $order->order_number,
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
                    Log::info('Generating Snap token for order: ' . $order->id);
                    $snapToken = Snap::getSnapToken($params);
                    $snapTokens[$order->id] = $snapToken;
                    Log::info('Snap token generated successfully for order: ' . $order->id);

                    DB::beginTransaction();

                    if (!$payment) {
                        $payment = new Payment();
                        $payment->order_id = $order->id;
                        Log::info('Creating NEW payment for order: ' . $order->id);
                    } else {
                        Log::info('Updating EXISTING payment for order: ' . $order->id);
                    }

                    $payment->order_number = $order->order_number;
                    $payment->total_price = $order->total_price;
                    $payment->customer_name = $order->customer_name ?? $user->name;
                    $payment->payment_method = 'midtrans';
                    $payment->payment_status = 'Menunggu Pembayaran';
                    $payment->amount = $order->total_price;
                    $payment->token = $snapToken;

                    if (!$payment->save()) {
                        throw new \Exception('Failed to save payment record');
                    }

                    DB::commit();
                    Log::info('Payment saved successfully for order: ' . $order->id . ', Payment ID: ' . $payment->id);

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Gagal membuat Snap token untuk order ' . $order->id . ': ' . $e->getMessage());
                    Log::error('Error details: ' . $e->getFile() . ':' . $e->getLine());
                    $snapTokens[$order->id] = null;
                }
            }
        }

        Log::info('=== PAYMENT PROCESS END ===');
        Log::info('Total snap tokens: ' . count(array_filter($snapTokens)));

        return view('payment.payment', compact('orders', 'snapTokens'));
    }

    /**
     * Check if any payment statuses for the authenticated user's orders have changed
     * Returns JSON with { updated: bool }
     */
    public function checkStatus(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['updated' => false, 'message' => 'User not authenticated']);
        }

        $orders = Order::with('payment')->where('user_id', $user->id)->get();

        // Here, we could compare current statuses with cached/previous status.
        // For simplicity, let's just return true if any payment is still waiting payment status.

        $hasWaitingPayment = $orders->contains(function ($order) {
            return $order->payment && $order->payment->payment_status === 'Menunggu Pembayaran';
        });

        return response()->json(['updated' => $hasWaitingPayment]);
    }

    public function debugPayments()
    {
        $user = Auth::user();
        $orders = Order::with('payment')->where('user_id', $user->id)->get();

        $debugInfo = [];
        foreach ($orders as $order) {
            $debugInfo[] = [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total_price' => $order->total_price,
                'weight' => $order->weight,
                'has_payment' => !is_null($order->payment),
                'payment_id' => $order->payment ? $order->payment->id : null,
                'payment_status' => $order->payment ? $order->payment->payment_status : 'No Payment',
                'payment_data' => $order->payment ? [
                    'order_number' => $order->payment->order_number,
                    'total_price' => $order->payment->total_price,
                    'customer_name' => $order->payment->customer_name,
                    'token' => $order->payment->token ? substr($order->payment->token, 0, 20) . '...' : 'No Token'
                ] : 'No Payment Data'
            ];
        }

        return response()->json($debugInfo);
    }

    public function forceCreatePayment($orderId)
    {
        $order = Order::findOrFail($orderId);
        $user = Auth::user();

        try {
            $payment = Payment::firstOrCreate(
                ['order_id' => $orderId],
                [
                    'order_number' => $order->order_number,
                    'total_price' => $order->total_price,
                    'customer_name' => $order->customer_name ?? $user->name,
                    'payment_method' => 'midtrans',
                    'payment_status' => 'Belum Dibayar',
                    'amount' => $order->total_price,
                    'token' => 'manual-temp-token-' . time()
                ]
            );

            Log::info('Force created payment for order: ' . $orderId . ', Payment ID: ' . $payment->id);
            return redirect()->route('payment.index')->with('success', 'Payment record created manually');

        } catch (\Exception $e) {
            Log::error('Force create payment failed: ' . $e->getMessage());
            return redirect()->route('payment.index')->with('error', 'Failed to create payment: ' . $e->getMessage());
        }
    }

    public function create($orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->weight <= 0) {
            return redirect()->route('orders.show', $orderId)
                ->with('error', 'Belum dapat melakukan pembayaran. Tunggu hingga weight diisi oleh admin.');
        }

        return redirect()->route('payment.index');
    }
}