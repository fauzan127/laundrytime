@extends('dashboard.layouts.main')
<title>Order</title>

@section('container')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold mb-4">Daftar Order</h1>
        <a href="{{ route('order.create') }}" class="bg-green-600 text-white px-4 py-2 rounded mt-4 hover:bg-green-700 transition duration-300">Tambah Order Baru</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Transaksi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Layanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Bayar</th>

                    {{-- Kolom yang hanya terlihat oleh Admin --}}
                    @if(Auth::check() && Auth::user()->role === 'admin')
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Telp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pembayaran</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $order->transaction_date ? \Carbon\Carbon::parse($order->transaction_date)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @forelse ($order->items as $item)
                                {{-- Tampilkan Jenis Layanan jika ada --}}
                                @if ($item->serviceType)
                                    <span class="inline-block bg-indigo-100 text-indigo-800 text-xs font-medium mr-1 mb-1 px-2.5 py-0.5 rounded-full">
                                        {{ $item->serviceType->name }}
                                    </span>
                                @endif
                        
                                {{-- Tampilkan Jenis Pakaian jika ada (gunakan warna yang berbeda/lebih lembut) --}}
                                @if ($item->clothingType)
                                    <span class="inline-block bg-gray-200 text-gray-700 text-xs font-medium mr-1 mb-1 px-2.5 py-0.5 rounded-full">
                                        {{ $item->clothingType->name }}
                                    </span>
                                @endif
                            @empty
                                <span class="text-xs text-red-500">Tidak ada item</span>
                            @endforelse
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'completed') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                        @if ($order->created_at == $order->updated_at) 
                            <span class="text-xs text-red-500">Menunggu Admin Update</span>
                        @else
                            <span class="text-lg text-green-700">
                                Rp{{ number_format($order->total_price, 0, ',', '.') }}
                            </span>
                        @endif
                        </td>

                        {{-- Data yang hanya terlihat oleh Admin --}}
                        @if(Auth::check() && Auth::user()->role === 'admin')
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $order->customer_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $order->customer_phone }}</td>
                            {{-- Asumsi 'customer_address' adalah kolom yang menyimpan alamat --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $order->address ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($order->payment_status == 'sudah_bayar') bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_status ?? '-')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex items-center justify-end space-x-3">
                                {{-- Tombol Edit (Ikon Pensil) --}}
                                <a href="{{ route('order.edit', $order->id) }}" title="Edit Pesanan"
                                   class="text-blue-600 hover:text-blue-800 flex items-center">
                                    {{-- SVG Icon Pensil --}}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                                {{-- Tombol Hapus (Ikon Tempat Sampah) --}}
                                <form action="{{ route('order.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini? Aksi ini tidak dapat dibatalkan.');" class="m-0 p-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus Pesanan"
                                            class="text-red-600 hover:text-red-800 flex items-center">
                                        {{-- SVG Icon Tempat Sampah (Trash) --}}
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ Auth::check() && Auth::user()->role === 'admin' ? 10 : 5 }}" class="text-center text-gray-500 py-10 text-lg">
                            Belum ada pesanan yang tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection