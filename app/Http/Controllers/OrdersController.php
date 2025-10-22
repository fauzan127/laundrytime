<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // ... method yang sudah ada ...

    /**
     * Update status order via API
     */
    public function updateStatus(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|integer|exists:orders,id',
                'status' => 'required|string|in:Diproses,Siap Diantar,Antar,Selesai,Dalam Pengerjaan,Pengantaran,Sampai Tujuan'
            ]);

            $order = Order::findOrFail($request->order_id);
            
            $order->update([
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diupdate',
                'data' => [
                    'id' => $order->id,
                    'status' => $order->status
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show detail order for kain keluar
     */
    public function showDetail($id)
    {
        try {
            $data = Order::where('id', $id)
                        ->select(
                            'customer_name as nama',
                            'customer_phone as no_hp',
                            'status',
                            'weight as berat',
                            'status as status_layanan',
                            'created_at as tanggal_masuk'

                        )
                        ->firstOrFail();

            return view('detailkainkeluar', compact('data'));
            
        } catch (\Exception $e) {
            abort(404, 'Data tidak ditemukan');
        }
    }
}