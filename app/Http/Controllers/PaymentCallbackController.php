<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
    
        $serverKey     = env('MIDTRANS_SERVER_KEY');
        $signatureKey  = $request->signature_key;
        $orderId       = $request->order_id;
        $statusCode    = $request->status_code;
        $grossAmount   = $request->gross_amount;
        $transactionStatus = $request->transaction_status;
        
        Log::info('📥 Callback masuk:', $request->all());

        // Validasi Signature
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        if ($expectedSignature !== $signatureKey) {
            Log::warning("❌ Signature tidak valid untuk order $orderId");
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Ambil ID asli dari order_id Midtrans
        $idParts = explode('-', $orderId);
        $orderIdReal = $idParts[1] ?? null;

        if (!$orderIdReal) {
            return response()->json(['message' => 'Order ID tidak valid'], 400);
        }

        $order = Order::with('payment')->find($orderIdReal);
        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        // Update status pembayaran jika transaksi sukses
        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            if ($order->payment) {
                $order->payment->update([
                    'payment_status' => 'Sudah Dibayar',
                    'paid_at' => now(),
                ]);
                Log::info("✅ Pembayaran berhasil untuk order #{$order->id}");
            } else {
                Log::warning("⚠️ Order #{$order->id} tidak memiliki relasi payment");
            }
        } else {
            Log::info("ℹ️ Transaksi untuk order #{$order->id} berstatus: $transactionStatus");
        }

        return response()->json(['message' => 'Callback diproses'], 200);

    }

}
