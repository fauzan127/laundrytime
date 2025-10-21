<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Update status order
     */
    public function updateStatus(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'order_id' => 'required|integer|exists:orders,id',
                'status' => 'required|string|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $orderId = $request->input('order_id');
            $newStatus = $request->input('status');

            // Cari order
            $order = Order::find($orderId);
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order tidak ditemukan'
                ], 404);
            }

            // Simpan status lama untuk logging
            $oldStatus = $order->status;

            // Update status
            $order->status = $newStatus;
            $order->updated_at = now();
            
            // Simpan perubahan
            if ($order->save()) {
                DB::commit();
                
                // Log perubahan status
                Log::info('Status order berhasil diupdate', [
                    'order_id' => $orderId,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'updated_at' => now(),
                    'user_id' => auth()->id() ?? 'system'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Status berhasil diupdate',
                    'data' => [
                        'order_id' => $orderId,
                        'status' => $newStatus,
                        'updated_at' => $order->updated_at
                    ]
                ]);
            } else {
                DB::rollBack();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan perubahan status'
                ], 500);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Gagal mengupdate status order: ' . $e->getMessage(), [
                'order_id' => $request->input('order_id'),
                'status' => $request->input('status'),
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available status options
     */
    public function getStatusOptions()
    {
        $statusOptions = [
            'Diproses' => 'Diproses',
            'Selesai' => 'Selesai', 
            'Dikirim' => 'Dikirim',
            'Dibatalkan' => 'Dibatalkan',
            'Pending' => 'Pending'
        ];

        return response()->json([
            'success' => true,
            'data' => $statusOptions
        ]);
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order_ids' => 'required|array',
                'order_ids.*' => 'integer|exists:orders,id',
                'status' => 'required|string|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $orderIds = $request->input('order_ids');
            $newStatus = $request->input('status');

            $updatedCount = Order::whereIn('id', $orderIds)
                                ->update([
                                    'status' => $newStatus,
                                    'updated_at' => now()
                                ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengupdate ' . $updatedCount . ' order',
                'data' => [
                    'updated_count' => $updatedCount
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal bulk update status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status multiple order: ' . $e->getMessage()
            ], 500);
        }
    }
}