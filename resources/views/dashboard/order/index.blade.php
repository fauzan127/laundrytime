@extends('dashboard.layouts.main')

@section('container')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Order</h1>
    <a href="{{ route('order.create') }}" class="bg-green-600 text-white px-4 py-2 rounded mt-4">Tambah Order</a>
    </div>
    <table class="w-full mt-4 border border-gray-200">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">No</th>
                <th class="p-2 border">Nama Pelanggan</th>
                <th class="p-2 border">Jenis Layanan</th>
                <th class="p-2 border">Jenis Pengantaran</th>
                <th class="p-2 border">Status</th>
                <th class="p-2 border">Berat</th>
                @if(Auth::check() && Auth::user()->role === 'admin')
                <th class="p-2 border">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order )
            <tr class="">
                <td class="p-2 border">{{ $loop->iteration }}</td>
                <td class="p-2 border">{{ $order->nama_pelanggan }}</td>
                <td class="p-2 border">{{ is_array($order->layanan) ? implode(', ', $order->layanan) : $order->layanan }}</td>
                <td class="p-2 border">
                            @if($order->jenis_pengantaran == 'antar_jemput')
                                Antar Jemput
                            @elseif($order->jenis_pengantaran == 'pengantaran_pribadi')
                                Pengantaran Pribadi
                            @else
                                -
                            @endif
                </td>
                <td class="p-2 border">
                            <span class="px-2 py-1 text-xs font-semibold rounded
                                @if($order->status == 'Pending') bg-yellow-200 text-yellow-800 
                                @elseif($order->status == 'Proses') bg-blue-200 text-blue-800 
                                @elseif($order->status == 'Selesai') bg-green-200 text-green-800 
                                @else bg-gray-200 text-gray-700 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                </td>
                <td class="p-2 border">3,2 kg</td>
                @if(Auth::check() && Auth::user()->role === 'admin')
                <td class="p-2 border space-x-2 flex items-center justify-center">
                    <a href="{{ route('order.edit', $order->id) }}" class="text-yellow-600">Edit</a>
                    <form action="{{ route('order.destroy', $order->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600">Hapus</button>
                    </form>
                </td>
                @endif
            </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-500 py-6">Belum ada pesanan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
