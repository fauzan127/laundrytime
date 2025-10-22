<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class KainMasukController extends Controller
{
    public function index()
{
    $data = Order::paginate(10);
    return view('dashboard.kain-masuk.index', compact('data'));
}


    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'address' => 'required|string',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'weight' => 'nullable|numeric',
            'total_price' => 'required|numeric',
            'status' => 'required|string',
            'delivery_type' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        Order::create($validated);

        return redirect()->route('kain-masuk.index')->with('success', 'Data kain masuk berhasil ditambahkan!');
    }
}
