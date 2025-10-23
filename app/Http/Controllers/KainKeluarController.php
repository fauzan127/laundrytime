<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class KainKeluarController extends Controller
{
    // === 1. Halaman utama (Dashboard Kain Keluar) ===
    public function index()
    {
        $data = Order::orderBy('id', 'asc')->get(['id', 'customer_name', 'delivery_type', 'status']);
        return view('dashboardkainkeluar', compact('data'));
    }

    // === 2. Update Status via AJAX ===
    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|in:diproses,slap_antar,antar,sampal_tujuan,cancelled',
        ]);

        try {
            $order = Order::findOrFail($request->order_id);
            $order->status = $request->status;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui.',
                'updated_status' => $order->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // === 3. Halaman detail kain keluar ===
    public function detailkainkeluar($id)
    {
        $data = Order::find($id);
        if (!$data) {
            abort(404, 'Data tidak ditemukan');
        }
        return view('detailkainkeluar', compact('data'));
    }

    // === 4. API list untuk AJAX (fetch data tabel) ===
    public function apiList()
    {
        $data = Order::orderBy('id', 'asc')->get();
        return response()->json($data);
    }

    // === 5. Simpan data baru ===
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'layanan' => 'required|string|max:100',
            'berat' => 'required|numeric|min:0',
            'status' => 'required|in:diproses,slap_antar,antar,sampal_tujuan,cancelled',
            'alamat' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        Order::create([
            'customer_name' => $validated['nama_pelanggan'],
            'customer_phone' => $validated['no_hp'],
            'delivery_type' => $validated['layanan'],
            'weight' => $validated['berat'],
            'status' => $validated['status'],
            'address' => $validated['alamat'],
            'notes' => $validated['catatan'],
            'total_price' => 0, // Default value
            'order_date' => now(),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('kain_keluar.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }

    // === 6. Update data lama ===
    public function update(Request $request, $id)
    {
        $data = Order::findOrFail($id);

        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'layanan' => 'required|string|max:100',
            'berat' => 'required|numeric|min:0',
            'status' => 'required|in:diproses,slap_antar,antar,sampal_tujuan,cancelled',
            'alamat' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $data->update([
            'customer_name' => $validated['nama_pelanggan'],
            'customer_phone' => $validated['no_hp'],
            'delivery_type' => $validated['layanan'],
            'weight' => $validated['berat'],
            'status' => $validated['status'],
            'address' => $validated['alamat'],
            'notes' => $validated['catatan'],
        ]);

        return redirect()->route('kain_keluar.index')
            ->with('success', 'Data berhasil diperbarui.');
    }
}
