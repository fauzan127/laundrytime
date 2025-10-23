<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    /**
     * 🔹 API: Ambil semua data pesanan (untuk fetch tabel via JS)
     * Route: GET /api/orders/list
     */
    public function getOrders()
    {
        try {
            // Ambil semua data dengan kolom yang dibutuhkan frontend
            $orders = Order::orderBy('id', 'asc')
                ->get(['id', 'customer_name as nama_pelanggan', 'customer_phone as no_hp', 'delivery_type as layanan', 'weight as berat', 'status', 'created_at']);

            return response()->json($orders);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data pesanan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🔹 API: Update status pesanan
     * Route: POST /api/update-status
     */
    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id',
            'status'   => 'required|string|in:Pending,Diproses,Antar,Sampai Tujuan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $order = Order::findOrFail($request->order_id);
            $order->status = $request->status;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => "Status pesanan #{$order->id} berhasil diubah menjadi {$order->status}",
                'order'   => $order, // ⬅️ kirim data terbaru biar JS bisa langsung update tampilan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status pesanan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🔹 Halaman dashboard kain keluar (Blade)
     * Route: GET /dashboard/kainkeluar
     */
    // Di OrdersController  
public function index()
{
    $data = Order::orderBy('created_at', 'asc')->get(); // Sudah benar
    return view('dashboardkainkeluar', compact('data'));
}

    /**
     * 🔹 Halaman detail kain keluar
     * Route: GET /detailkainkeluar/{id}
     */
    public function detail($id)
    {
        $data = Order::find($id);

        if (!$data) {
            abort(404, 'Data tidak ditemukan');
        }

        return view('detailkainkeluar', compact('data'));
    }
}