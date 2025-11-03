@extends('dashboard.layouts.main')
<title>Order</title>

@section('container')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold mb-4">Daftar Order</h1>
        <a href="{{ route('order.create') }}" class="bg-green-600 text-white px-4 py-2 rounded mt-4">Tambah Order</a>
    </div>

    <table class="w-full mt-4 border border-gray-200">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2">No</th>
                <th class="px-4 py-2">Nama Pelanggan</th>
                <th class="px-4 py-2">No. Telp</th>
                <th class="px-4 py-2">Total Bayar</th>
                <th class="px-4 py-2">Status Pesanan</th>
                <th class="px-4 py-2">Status Pembayaran</th>
                <th class="px-4 py-2">Tanggal Transaksi</th>
                @if(Auth::check() && Auth::user()->role === 'admin')
                    <th class="px-4 py-2">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-2 text-center">{{ $loop->iteration }}</td>
                    <td class="p-2">{{ $order->customer_name }}</td>
                    <td class="p-2">{{ $order->customer_phone }}</td>
                    <td class="p-2">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="p-2">
                        <span class="px-2 py-1 text-xs font-semibold rounded
                            @if($order->status == 'pending') bg-yellow-200 text-yellow-800
                            @elseif($order->status == 'processing') bg-blue-200 text-blue-800
                            @elseif($order->status == 'completed') bg-green-200 text-green-800
                            @else bg-gray-200 text-gray-700 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="p-2">
                        <span class="px-2 py-1 text-xs font-semibold rounded
                            @if($order->payment_status == 'sudah_bayar') bg-green-100 text-green-700
                            @else bg-red-100 text-red-700
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->payment_status ?? '-')) }}
                        </span>
                    </td>
                    <td class="p-2">
                        {{ $order->transaction_date ? \Carbon\Carbon::parse($order->transaction_date)->format('d M Y') : '-' }}
                    </td>
                    @if(Auth::check() && Auth::user()->role === 'admin')
                        <td class="p-2 text-center">
                            <a href="{{ route('order.edit', $order->id) }}" class="text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('order.destroy', $order->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-gray-500 py-6">Belum ada pesanan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
