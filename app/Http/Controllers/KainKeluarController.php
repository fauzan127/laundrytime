<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KainKeluarController extends Controller
{
    public function index()
    {
        $data = Order::all();
        return view('dashboardkainkeluar', compact('data'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'status' => 'required|string'
        ]);

        $order = Order::find($request->order_id);
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui.',
            'updated_status' => $request->status
        ]);
    }

    public function detailkainkeluar($id)
    {
        $data = Order::findOrFail($id);
        return view('detailkainkeluar', compact('data'));
    }

    public function apiList()
    {
        return response()->json(Order::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required',
            'no_hp' => 'required',
            'layanan' => 'required',
            'berat' => 'required|numeric',
            'status' => 'required',
            'alamat' => 'required',
            'catatan' => 'nullable'
        ]);

        Order::create([
            'customer_name' => $request->nama_pelanggan,
            'customer_phone' => $request->no_hp,
            'delivery_type' => $request->layanan,
            'weight' => $request->berat,
            'status' => $request->status,
            'address' => $request->alamat,
            'notes' => $request->catatan,
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . time(),
            'order_date' => now(),
            'total_price' => 0
        ]);

        return redirect()->route('kain_keluar.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            abort(404);
        }

        $request->validate([
            'nama_pelanggan' => 'required',
            'no_hp' => 'required',
            'layanan' => 'required',
            'berat' => 'required|numeric',
            'status' => 'required',
            'alamat' => 'required',
            'catatan' => 'nullable'
        ]);

        $order->customer_name = $request->nama_pelanggan;
        $order->customer_phone = $request->no_hp;
        $order->delivery_type = $request->layanan;
        $order->weight = $request->berat;
        $order->status = $request->status;
        $order->address = $request->alamat;
        $order->notes = $request->catatan;

        if ($request->hasFile('file')) {
            // handle file, e.g. $order->image = $request->file('file')->store('images');
        }

        $order->save();

        return redirect()->route('kain_keluar.index')->with('success', 'Data berhasil diperbarui.');
    }
}
