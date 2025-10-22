<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ServiceType;
use App\Models\ClothingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index()
    {
        $orders = Order::with(['items.serviceType', 'items.clothingType', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('dashboard.order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create()
    {
        $serviceTypes = ServiceType::where('is_active', true)->get();
        $clothingTypes = ClothingType::where('is_active', true)->get();
        
        return view('dashboard.order.create', compact('serviceTypes', 'clothingTypes'));
    }

    /**
     * Store a newly created order in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_type' => 'required|in:antar_jemput,pengantaran_pribadi',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.service_type_id' => 'required|exists:service_types,id',
            'items.*.clothing_type_id' => 'required|exists:clothing_types,id',
            'items.*.weight' => 'required|numeric|min:0.1|max:1000',
        ]);

        // Validasi khusus untuk alamat jika delivery_type adalah antar_jemput
        if ($validated['delivery_type'] === 'antar_jemput') {
            if (empty($validated['address'])) {
                return back()->withInput()->with('error', 'Alamat wajib diisi untuk layanan Antar Jemput.');
            }
            
            $address = strtolower($validated['address']);
            if (!str_contains($address, 'tuah karya') && !str_contains($address, 'tuahkarya')) {
                return back()->withInput()->with('error', 'Alamat harus berada di area Tuah Karya untuk layanan Antar Jemput.');
            }
        }

        DB::beginTransaction();
        try {
            // Create order
            $order = Order::create([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'delivery_type' => $validated['delivery_type'],
                'address' => $validated['address'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'order_date' => now(),
                'status' => 'Pending',
                'user_id' => Auth::id(),
            ]);

            $totalPrice = 0;

            // Create order items
            foreach ($validated['items'] as $item) {
                $serviceType = ServiceType::findOrFail($item['service_type_id']);
                $clothingType = ClothingType::findOrFail($item['clothing_type_id']);
                
                $itemPrice = ($serviceType->price_per_kg + $clothingType->additional_price) * $item['weight'];
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'service_type_id' => $item['service_type_id'],
                    'clothing_type_id' => $item['clothing_type_id'],
                    'weight' => $item['weight'],
                    'price' => $itemPrice
                ]);

                $totalPrice += $itemPrice;
            }

            // Update total price
            $order->update(['total_price' => $totalPrice]);

            DB::commit();

            return redirect()->route('order.index')->with('success', 'Pesanan berhasil dibuat! Total: Rp ' . number_format($totalPrice, 0, ',', '.'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['items.serviceType', 'items.clothingType', 'user']);
        return view('dashboard.order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order
     */
    public function edit(Order $order)
    {
        // Only admin can edit
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['items.serviceType', 'items.clothingType']);
        $serviceTypes = ServiceType::where('is_active', true)->get();
        $clothingTypes = ClothingType::where('is_active', true)->get();

        return view('dashboard.order.edit', compact('order', 'serviceTypes', 'clothingTypes'));
    }

    /**
     * Update the specified order in storage
     */
    public function update(Request $request, Order $order)
    {
        // Only admin can update
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_type' => 'required|in:antar_jemput,pengantaran_pribadi',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:Pending,Proses,Selesai',
            'items' => 'required|array|min:1',
            'items.*.service_type_id' => 'required|exists:service_types,id',
            'items.*.clothing_type_id' => 'required|exists:clothing_types,id',
            'items.*.weight' => 'required|numeric|min:0.1|max:1000',
        ]);

        // Validasi khusus untuk alamat jika delivery_type adalah antar_jemput
        if ($validated['delivery_type'] === 'antar_jemput') {
            if (empty($validated['address'])) {
                return back()->withInput()->with('error', 'Alamat wajib diisi untuk layanan Antar Jemput.');
            }
            
            $address = strtolower($validated['address']);
            if (!str_contains($address, 'tuah karya') && !str_contains($address, 'tuahkarya')) {
                return back()->withInput()->with('error', 'Alamat harus berada di area Tuah Karya untuk layanan Antar Jemput.');
            }
        }

        DB::beginTransaction();
        try {
            // Update order basic info
            $order->update([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'delivery_type' => $validated['delivery_type'],
                'address' => $validated['address'] ?? null,
                'status' => $validated['status'],
            ]);

            // Delete old items
            $order->items()->delete();

            $totalPrice = 0;

            // Create new items
            foreach ($validated['items'] as $item) {
                $serviceType = ServiceType::findOrFail($item['service_type_id']);
                $clothingType = ClothingType::findOrFail($item['clothing_type_id']);
                
                $itemPrice = ($serviceType->price_per_kg + $clothingType->additional_price) * $item['weight'];
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'service_type_id' => $item['service_type_id'],
                    'clothing_type_id' => $item['clothing_type_id'],
                    'weight' => $item['weight'],
                    'price' => $itemPrice
                ]);

                $totalPrice += $itemPrice;
            }

            // Update total price
            $order->update(['total_price' => $totalPrice]);

            DB::commit();

            return redirect()->route('order.index')->with('success', 'Pesanan berhasil diupdate!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    /**
     * Remove the specified order from storage
     */
    public function destroy(Order $order)
    {
        // Only admin can delete
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            // Delete order items first (cascade should handle this, but being explicit)
            $order->items()->delete();
            
            // Delete order
            $order->delete();
            
            DB::commit();
            
            return redirect()->route('order.index')->with('success', 'Pesanan berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}