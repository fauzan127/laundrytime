<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class KainKeluarController extends Controller
{
    // Tampilkan dashboard kain keluar
    public function index()
    {
        $data = Order::orderBy('id', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('dashboardkainkeluar', compact('data'));
    }

    public function updateStatus(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'status' => 'required|in:Pending,Diproses,Sampai Tujuan,Antar,'
    ]);

    try {
        $order = Order::find($request->order_id);
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diupdate'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengupdate status'
        ], 500);
    }
}

    // Tampilkan detail kain keluar
    public function detailkainkeluar($id)
    {
        $data = Order::find($id);
        
        if (!$data) {
            abort(404, 'Data tidak ditemukan');
        }
        
        return view('detailkainkeluar', compact('data'));
    }

    // API untuk JS
    public function apiList()
    {
        $data = Order::orderBy('id', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($data);
    }

    
    // Method store dengan field yang sesuai tabel
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',    // SESUAI TABEL
            'no_hp' => 'required|string|max:255',           // SESUAI TABEL  
            'layanan' => 'required|string|max:50',           // SESUAI TABEL
            'berat' => 'required|numeric|min:0',            // SESUAI TABEL
            'status' => 'required|in:Diproses,,Pengantaran,Sampai Tujuan',
            'jenis_pengantaran' => 'nullable|string',
            'alamat' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        Order::create($validated);

        return redirect()->route('kain_keluar.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }

    // Method update juga sesuaikan
    public function update(Request $request, $id)
    {
        $data = Order::findOrFail($id);

        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:255', 
            'layanan' => 'required|string|max:50',
            'berat' => 'required|numeric|min:0',
            'status' => 'required|in:Diproses,Sampai Tujuan,Antar,',
            'jenis_pengantaran' => 'nullable|string',
            'alamat' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $data->update($validated);

        return redirect()->route('kain_keluar.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    
}