<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ServiceType;
use App\Models\ClothingType;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    /**
     * Tampilkan daftar order
     */
    public function index()
    {
        // 1. Mulai query dasar dengan eager loading
        $query = Order::with('items.serviceType', 'items.clothingType') // Tambah clothingType agar lengkap
        ->orderBy('created_at', 'desc');
            
        // 2. Terapkan filtering berdasarkan peran pengguna
        if (Auth::check() && Auth::user()->role !== 'admin') {
        // Jika pengguna adalah user biasa (bukan admin),
        // filter hanya order yang user_id nya sesuai dengan ID user yang sedang login.
        $query->where('user_id', Auth::id());
        }
        
        // 3. Eksekusi query dengan pagination
        $orders = $query->paginate(10);
        
        // Pastikan nama view sesuai, saya asumsikan 'dashboard.order.index' dari kode Anda
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
        $rules = [
            'delivery_type' => 'required|in:antar_jemput,pengantaran_pribadi',
            'address' => 'nullable|string|max:255',
            'pickup_date' => 'nullable|date',
            'pickup_time' => 'nullable',
            'notes' => 'nullable|string|max:500',
            'payment_status' => 'nullable|in:belum_bayar,sudah_bayar',
            'transaction_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.service_type_id' => 'nullable|exists:service_types,id',
            'items.*.clothing_type_id' => 'nullable|exists:clothing_types,id',
            'items.*.weight' => 'required|numeric|min:0.1|max:1000',
        ];

        if (Auth::user()->role === 'admin') {
            $rules['customer_name'] = 'required|string|max:255';
            $rules['customer_phone'] = 'required|string|max:20';
        }

        $validated = $request->validate($rules);

        if (!is_array($validated['items'])) {
            return back()->withInput()->with('error', 'Data items must be an array.');
        }
        foreach ($validated['items'] as $index => $item) {
            if (empty($item['service_type_id']) && empty($item['clothing_type_id'])) {
                return back()->withInput()->with('error', "Item " . ($index + 1) . ": Harus memilih setidaknya satu jenis layanan atau jenis pakaian.");
            }
        }

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
            $customerName = Auth::user()->role === 'admin'
                ? $validated['customer_name']
                : Auth::user()->name;

            $customerPhone = Auth::user()->role === 'admin'
                ? $validated['customer_phone']
                : Auth::user()->phone;

            $order = Order::create([
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'delivery_type' => $validated['delivery_type'],
                'address' => $validated['address'] ?? null,
                'pickup_date' => $validated['pickup_date'] ?? null,
                'pickup_time' => $validated['pickup_time'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'diproses',
                'payment_status' => $validated['payment_status'] ?? 'belum_bayar',
                'transaction_date' => $validated['transaction_date'] ?? now(),
                'user_id' => Auth::id(),
                'order_date' => now(),
                'weight' => 0,
            ]);

            $totalPrice = 0;
            $totalWeight = 0;
            $satuanCounts = [];
            $satuanKeywords = ['bedcover', 'sprei', 'selimut', 'boneka'];

            foreach ($validated['items'] as $item) {
                $servicePrice = 0;
                $clothingPrice = 0;

                $clothingType = null;
                $satuanKey = null;
                if (!empty($item['clothing_type_id'])) {
                    $clothingType = ClothingType::find($item['clothing_type_id']);
                    if ($clothingType) {
                        $name = strtolower($clothingType->name);
                        foreach ($satuanKeywords as $keyword) {
                            if (stripos($name, $keyword) !== false) {
                                $satuanKey = $keyword;
                                break;
                            }
                        }
                    }
                }

                if ($satuanKey) {
                    $satuanCounts[$satuanKey] = ($satuanCounts[$satuanKey] ?? 0) + 1;
                } else {
                    $totalWeight += $item['weight'];
                }

                if (!empty($item['service_type_id'])) {
                    $serviceType = ServiceType::find($item['service_type_id']);
                    $servicePrice = $serviceType->price_per_kg;
                }

                if ($clothingType) {
                    $clothingPrice = $clothingType->additional_price;
                }

                $itemPrice = ($servicePrice + $clothingPrice) * $item['weight'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'service_type_id' => $item['service_type_id'] ?? null,
                    'clothing_type_id' => $item['clothing_type_id'] ?? null,
                    'weight' => $item['weight'],
                    'price' => $itemPrice,
                ]);

                $totalPrice += $itemPrice;
            }

            $order->update([
                'total_price' => $totalPrice,
                'weight' => $totalWeight,
                'satuan_counts' => json_encode($satuanCounts),
            ]);

            DB::commit();

            return redirect()->route('order.index')->with('success', 'Pesanan berhasil dibuat!');
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
            'pickup_date' => 'nullable|date',
            'pickup_time' => 'nullable',
            'notes' => 'nullable|string|max:500',
            'payment_status' => 'nullable|in:belum_bayar,sudah_bayar',
            'transaction_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.service_type_id' => 'nullable|exists:service_types,id',
            'items.*.clothing_type_id' => 'nullable|exists:clothing_types,id',
            'items.*.weight' => 'required|numeric|min:0.1|max:1000',
        ]);

        // Validate items: at least one of service_type_id or clothing_type_id must be present
        if (!is_array($validated['items'])) {
            return back()->with('error', 'Data items must be an array.');
        }        
        foreach ($validated['items'] as $index => $item) {
            if (empty($item['service_type_id']) && empty($item['clothing_type_id'])) {
                return back()->with('error', "Item " . ($index + 1) . ": Harus memilih setidaknya satu jenis layanan atau jenis pakaian.");
            }
        }

        DB::beginTransaction();
        try {
            $order->update([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'delivery_type' => $validated['delivery_type'],
                'address' => $validated['address'] ?? null,
                'pickup_date' => $validated['pickup_date'] ?? null,
                'pickup_time' => $validated['pickup_time'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'payment_status' => $validated['payment_status'] ?? $order->payment_status,
                'transaction_date' => $validated['transaction_date'] ?? $order->transaction_date,
                // Don't update weight here yet, will do later after calculating
            ]);

            // Hapus item lama
            $order->items()->delete();

            $totalPrice = 0;
            $totalWeight = 0;
            $satuanCounts = [];
            $satuanKeywords = ['bedcover', 'sprei', 'selimut', 'boneka'];

            foreach ($validated['items'] as $item) {
                $servicePrice = 0;
                $clothingPrice = 0;

                $clothingType = null;
                $satuanKey = null;
                if (!empty($item['clothing_type_id'])) {
                    $clothingType = ClothingType::find($item['clothing_type_id']);
                    if ($clothingType) {
                        $name = strtolower($clothingType->name);
                        foreach ($satuanKeywords as $keyword) {
                            if (stripos($name, $keyword) !== false) {
                                $satuanKey = $keyword;
                                break;
                            }
                        }
                    }
                }

                if ($satuanKey) {
                    $satuanCounts[$satuanKey] = ($satuanCounts[$satuanKey] ?? 0) + 1;
                } else {
                    $totalWeight += $item['weight'];
                }

                if (!empty($item['service_type_id'])) {
                    $serviceType = ServiceType::find($item['service_type_id']);
                    $servicePrice = $serviceType->price_per_kg;
                }

                if ($clothingType) {
                    $clothingPrice = $clothingType->additional_price;
                }

                $itemPrice = ($servicePrice + $clothingPrice) * $item['weight'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'service_type_id' => $item['service_type_id'] ?? null,
                    'clothing_type_id' => $item['clothing_type_id'] ?? null,
                    'weight' => $item['weight'],
                    'price' => $itemPrice,
                ]);

                $totalPrice += $itemPrice;
            }

            $order->update([
                'total_price' => $totalPrice,
                'weight' => $totalWeight, // Only regular weight
                'satuan_counts' => json_encode($satuanCounts),
            ]);

            // Handle payment record after weight update
            if ($totalWeight > 0) {
                $payment = $order->payment;
                if (!$payment) {
                    // Create new payment record
                    $payment = Payment::create([
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'total_price' => $totalPrice,
                        'customer_name' => $order->customer_name,
                        'payment_method' => 'midtrans',
                        'payment_status' => 'Belum Dibayar',
                        'amount' => $totalPrice,
                    ]);
                } else {
                    // Update existing payment and "finalize" it by setting updated_at = created_at
                    $payment->update([
                        'total_price' => $totalPrice,
                        'amount' => $totalPrice,
                        'payment_status' => 'Belum Dibayar',
                        'updated_at' => $payment->created_at, // This makes created_at == updated_at, marking as finalized
                    ]);
                }

                // Generate Midtrans token for finalized payment
                try {
                    Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                    Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', true);
                    Config::$isSanitized = true;
                    Config::$is3ds = true;

                    $orderId = 'ORDER-' . $order->id . '-' . uniqid();

                    $params = [
                        'transaction_details' => [
                            'order_id' => $orderId,
                            'gross_amount' => (int) $totalPrice,
                        ],
                        'customer_details' => [
                            'first_name' => $order->customer_name,
                            'email' => $order->user->email ?? 'customer@example.com',
                            'phone' => $order->customer_phone ?? '081234567890',
                        ],
                        'item_details' => [
                            [
                                'id' => 'order-' . $order->id,
                                'price' => (int) $totalPrice,
                                'quantity' => 1,
                                'name' => 'Laundry Service - ' . $order->order_number,
                            ]
                        ],
                        'enabled_payments' => [
                            'credit_card',
                            'gopay',
                            'qris',
                            'bank_transfer',
                            'shopeepay',
                            'permata_va',
                            'bca_va',
                            'bni_va',
                            'other_va',
                        ],
                    ];

                    $snapToken = Snap::getSnapToken($params);

                    $payment->update([
                        'token' => $snapToken,
                        'payment_status' => 'Menunggu Pembayaran', // Change to waiting for payment after token is generated
                    ]);

                    Log::info('Token generated successfully for finalized payment order: ' . $order->id);

                } catch (\Exception $e) {
                    Log::error('Failed to generate token for finalized payment order ' . $order->id . ': ' . $e->getMessage());
                    // Don't fail the entire transaction if token generation fails
                    // The payment will still be finalized, and token can be generated later in PaymentController
                }
            }

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
