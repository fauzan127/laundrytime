<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\KainKeluar; //

class TrackingController extends Controller
{
    public function index()
{
    // Ambil semua order yang status-nya BUKAN 'Diproses'
    $orders = Order::where('status', '!=', 'Diproses')
                   ->orderBy('created_at', 'asc')
                   ->get();

    return view('dashboard.tracking', compact('orders'));
}

public function updateStatus(Request $request, $id)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'status' => 'required|string'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ]);
    }

    // Cari order berdasarkan ID
    $order = \App\Models\Order::find($id);

    if (!$order) {
        return response()->json([
            'success' => false,
            'message' => 'Order tidak ditemukan'
        ]);
    }

    // Update status
    $order->status = $request->status;
    $order->save();

    return response()->json([
        'success' => true,
        'message' => 'Status berhasil diperbarui'
    ]);
}



    public function apiList()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function show($customerName)
{
    // Ambil semua order dengan nama pelanggan sama
    $orders = Order::where('customer_name', $customerName)->get();

    return view('dashboard.detailtracking', compact('orders'));
}


}
