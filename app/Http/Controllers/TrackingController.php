<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function index()
    {
        // Hanya tampilkan order yang statusnya bukan 'cancelled' dan 'sampai_tujuan'
        $orders = Order::whereNotIn('status', ['cancelled', 'sampai_tujuan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $statusCounts = [
            'total' => Order::count(),
            'diproses' => Order::where('status', 'diproses')->count(),
            'siap_antar' => Order::where('status', 'siap_antar')->count(),
            'antar' => Order::where('status', 'antar')->count(),
            'sampai_tujuan' => Order::where('status', 'sampai_tujuan')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count()
        ];

        return view('dashboard.tracking', compact('orders', 'statusCounts'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            // DEBUG: Log semua data yang masuk
            Log::info('================ UPDATE STATUS DEBUG ================');
            Log::info('Order ID: ' . $id);
            Log::info('Request Method: ' . $request->method());
            Log::info('IP Address: ' . $request->ip());
            Log::info('User Agent: ' . $request->userAgent());
            Log::info('Request Data:', $request->all());
            
            // Log headers secara manual
            Log::info('Headers - Content-Type: ' . $request->header('Content-Type'));
            Log::info('Headers - X-Requested-With: ' . $request->header('X-Requested-With'));
            Log::info('Headers - Accept: ' . $request->header('Accept'));

            // Validasi
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:diproses,siap_antar,antar,sampai_tujuan,cancelled'
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed:', ['errors' => $validator->errors()->toArray()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            // Cari order
            $order = Order::find($id);
            if (!$order) {
                Log::error('Order not found with ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Order tidak ditemukan'
                ], 404);
            }

            Log::info('Order found - Before update:', [
                'id' => $order->id,
                'customer_name' => $order->customer_name,
                'old_status' => $order->status,
                'new_status' => $request->status
            ]);

            // Cek apakah status sama dengan yang lama
            if ($order->status === $request->status) {
                Log::info('Status unchanged, no update needed');
                return response()->json([
                    'success' => true,
                    'message' => 'Status berhasil diperbarui',
                    'data' => [
                        'id' => $order->id,
                        'customer_name' => $order->customer_name,
                        'status' => $order->status,
                        'unchanged' => true
                    ]
                ]);
            }

            // Simpan status lama untuk log
            $oldStatus = $order->status;

            // Update status
            $order->status = $request->status;
            $saved = $order->save();

            if (!$saved) {
                Log::error('Failed to save order');
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan ke database'
                ], 500);
            }

            // Verifikasi perubahan
            $updatedOrder = Order::find($id);
            Log::info('Order after update - Verified:', [
                'id' => $updatedOrder->id,
                'status' => $updatedOrder->status
            ]);

            Log::info('================ UPDATE STATUS SUCCESS ================');

            // Jika status berubah, tampilkan pesan dengan perubahan
            if ($oldStatus !== $request->status) {
                $message = "Status berhasil diperbarui dari \"{$oldStatus}\" ke \"{$request->status}\"";
                
                // Tambahkan pesan khusus jika status diubah menjadi 'cancelled' atau 'sampai_tujuan'
                if (in_array($request->status, ['cancelled', 'sampai_tujuan'])) {
                    $message .= ". Order ini tidak akan lagi ditampilkan di halaman tracking utama.";
                }
            } else {
                $message = "Status berhasil diperbarui";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'id' => $updatedOrder->id,
                    'customer_name' => $updatedOrder->customer_name,
                    'status' => $updatedOrder->status,
                    'old_status' => $oldStatus,
                    'new_status' => $request->status,
                    'hidden_from_main' => in_array($request->status, ['cancelled', 'sampai_tujuan'])
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Exception in updateStatus:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($customerName)
    {
        // Untuk halaman detail, tetap tampilkan semua status termasuk yang cancelled dan sampai_tujuan
        $orders = Order::where('customer_name', $customerName)->get();
        return view('dashboard.detailtracking', compact('orders'));
    }

    /**
     * Method untuk menampilkan order yang sudah selesai (cancelled dan sampai_tujuan)
     */
    public function completedOrders()
    {
        $orders = Order::whereIn('status', ['cancelled', 'sampai_tujuan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.completed-orders', compact('orders'));
    }
}