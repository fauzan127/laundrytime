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
     * Tampilkan daftar order
     */
    public function index()
    {
        // Ambil semua order terbaru
        $orders = Order::orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard.order.index', compact('orders'));
    }

    /**
     * Tampilkan form tambah order
     */
    public function create()
    {
        $serviceTypes = ServiceType::where('is_active', true)->get();
        $clothingTypes = ClothingType::where('is_active', true)->get();

        return view('dashboard.order.create', compact('serviceTypes', 'clothingTypes'));
    }

    /**
     * Simpan order baru
     */
    public function store(Request $request)
    {


        // Validasi input
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_type' => 'required|in:antar_jemput,pengantaran_pribadi',
            'address' => 'nullable|string|max:255',
            'pickup_date' => 'nullable|date',
            'pickup_time' => 'nullable',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.service_type_id' => 'required|exists:service_types,id',
            'items.*.clothing_type_id' => 'required|exists:clothing_types,id',
            'items.*.weight' => 'required|numeric|min:0.1|max:1000',
        ]);

        // Jika antar jemput, wajib isi alamat & cek area
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
            // Simpan order
            $order = Order::create([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'delivery_type' => $validated['delivery_type'],
                'address' => $validated['address'] ?? null,
                'pickup_date' => $validated['pickup_date'] ?? null,
                'pickup_time' => $validated['pickup_time'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'diproses',
                'user_id' => Auth::id(),
                'order_date' => now(),
            ]);

            $totalPrice = 0;

            // Simpan item pesanan
            foreach ($validated['items'] as $item) {
                $serviceType = ServiceType::findOrFail($item['service_type_id']);
                $clothingType = ClothingType::findOrFail($item['clothing_type_id']);

                $itemPrice = ($serviceType->price_per_kg + $clothingType->additional_price) * $item['weight'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'service_type_id' => $item['service_type_id'],
                    'clothing_type_id' => $item['clothing_type_id'],
                    'weight' => $item['weight'],
                    'price' => $itemPrice,
                ]);

                $totalPrice += $itemPrice;
            }

            // Update total harga
            $order->update(['total_price' => $totalPrice]);

            DB::commit();

            return redirect()->route('order.index')
                ->with('success', 'Pesanan berhasil dibuat! Total: Rp ' . number_format($totalPrice, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Detail order
     */
    public function show(Order $order)
    {
        $order->load(['items.serviceType', 'items.clothingType', 'user']);
        return view('dashboard.order.show', compact('order'));
    }

    /**
     * Form edit order (khusus admin)
     */
    public function edit(Order $order)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['items.serviceType', 'items.clothingType']);
        $serviceTypes = ServiceType::where('is_active', true)->get();
        $clothingTypes = ClothingType::where('is_active', true)->get();

        return view('dashboard.order.edit', compact('order', 'serviceTypes', 'clothingTypes'));
    }

    /**
     * Update order
     */
    public function update(Request $request, Order $order)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_type' => 'required|in:antar_jemput,pengantaran_pribadi',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:diproses,siap_antar,antar,sampai_tujuan,cancelled',
            'items' => 'required|array|min:1',
            'items.*.service_type_id' => 'required|exists:service_types,id',
            'items.*.clothing_type_id' => 'required|exists:clothing_types,id',
            'items.*.weight' => 'required|numeric|min:0.1|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $order->update([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'delivery_type' => $validated['delivery_type'],
                'address' => $validated['address'] ?? null,
                'status' => $validated['status'],
            ]);

            // Hapus item lama
            $order->items()->delete();

            $totalPrice = 0;
            foreach ($validated['items'] as $item) {
                $serviceType = ServiceType::findOrFail($item['service_type_id']);
                $clothingType = ClothingType::findOrFail($item['clothing_type_id']);
                $itemPrice = ($serviceType->price_per_kg + $clothingType->additional_price) * $item['weight'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'service_type_id' => $item['service_type_id'],
                    'clothing_type_id' => $item['clothing_type_id'],
                    'weight' => $item['weight'],
                    'price' => $itemPrice,
                ]);

                $totalPrice += $itemPrice;
            }

            $order->update(['total_price' => $totalPrice]);
            DB::commit();

            return redirect()->route('order.index')->with('success', 'Pesanan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus order
     */
    public function destroy(Order $order)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            $order->items()->delete();
            $order->delete();
            DB::commit();

            return redirect()->route('order.index')->with('success', 'Pesanan berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
