<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return view('dashboard.order.index', compact('orders'));
    }

    public function create()
    {
        return view('dashboard.order.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'layanan' => 'required|array',
            'delivery_type' => 'required|in:antar_jemput,pengantaran_pribadi',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Order::create([
            'nama_pelanggan' => $request->customer_name,
            'no_hp' => $request->customer_phone,
            'layanan' => $request->layanan,
            'jenis_pengantaran' => $request->delivery_type,
            'alamat' => $request->address,
            'catatan' => $request->notes,
            'status' => 'Pending',
        ]);

        return redirect()->route('order.index')->with('success', 'Order berhasil dibuat!');
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('dashboard.order.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'status' => 'required|in:Pending,Proses,Selesai',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'nama_pelanggan' => $request->nama,
            'status' => $request->status,
        ]);

        return redirect()->route('order.index')->with('success', 'Order berhasil diupdate!');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('order.index')->with('success', 'Order berhasil dihapus!');
    }
}
