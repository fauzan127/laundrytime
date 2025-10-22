@extends('dashboard.layouts.main')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-green-700">Cuci Kain Masuk</h2>

        <!-- Form Pencarian -->
        <form action="{{ route('orders.index') }}" method="GET" class="flex">
            <input 
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama atau no HP..."
                class="border border-green-600 rounded-l-lg px-4 py-2 focus:outline-none"
            >
            <button type="submit" class="bg-green-600 text-white px-4 rounded-r-lg hover:bg-green-700">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <!-- Notifikasi -->
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Tambah Data -->
    <form action="{{ route('orders.store') }}" method="POST" class="bg-white p-4 rounded-lg shadow mb-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" name="customer_name" placeholder="Nama Pelanggan" class="border p-2 rounded" required>
            <input type="text" name="customer_phone" placeholder="No. HP" class="border p-2 rounded" required>
            <input type="text" name="address" placeholder="Alamat" class="border p-2 rounded" required>
            <input type="date" name="pickup_date" class="border p-2 rounded" required>
            <input type="time" name="pickup_time" class="border p-2 rounded" required>
            <input type="number" name="weight" step="0.01" placeholder="Berat (kg)" class="border p-2 rounded">
            <input type="number" name="total_price" placeholder="Total Bayar" class="border p-2 rounded" required>

            <select name="status" class="border p-2 rounded" required>
                <option value="">-- Status Pesanan --</option>
                <option value="pending">Pending</option>
                <option value="proses">Proses</option>
                <option value="selesai">Selesai</option>
            </select>

            <select name="delivery_type" class="border p-2 rounded" required>
                <option value="">-- Jenis Pengantaran --</option>
                <option value="antar_jemput">Antar Jemput</option>
                <option value="antar_pribadi">Antar Pribadi</option>
            </select>
        </div>

        <div class="mt-4">
            <textarea name="notes" rows="3" placeholder="Catatan (opsional)" class="border p-2 rounded w-full"></textarea>
        </div>

        <div class="mt-4 text-right">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Tambah Data
            </button>
        </div>
    </form>

    <!-- Tabel Data -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full text-sm border border-gray-200">
            <thead class="bg-green-200">
                <tr>
                    <th class="border px-3 py-2">No</th>
                    <th class="border px-3 py-2">Nama</th>
                    <th class="border px-3 py-2">No. HP</th>
                    <th class="border px-3 py-2">Alamat</th>
                    <th class="border px-3 py-2">Tanggal Pickup</th>
                    <th class="border px-3 py-2">Total</th>
                    <th class="border px-3 py-2">Status</th>
                    <th class="border px-3 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $index => $item)
                    <tr class="{{ $loop->even ? 'bg-gray-100' : 'bg-white' }}">
                        <td class="border px-3 py-2 text-center">{{ $index + 1 }}</td>
                        <td class="border px-3 py-2">{{ $item->customer_name }}</td>
                        <td class="border px-3 py-2">{{ $item->customer_phone }}</td>
                        <td class="border px-3 py-2">{{ $item->address }}</td>
                        <td class="border px-3 py-2 text-center">{{ \Carbon\Carbon::parse($item->pickup_date)->format('d/m/Y') }}</td>
                        <td class="border px-3 py-2 text-right">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                        <td class="border px-3 py-2 text-center">
                            <span class="px-2 py-1 rounded text-white 
                                @if($item->status == 'pending') bg-yellow-500 
                                @elseif($item->status == 'proses') bg-blue-500 
                                @else bg-green-600 @endif">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="border px-3 py-2 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('orders.edit', $item->id) }}" class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">Edit</a>
                                <form action="{{ route('orders.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-gray-500">Belum ada data kain masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex justify-center">
        {{ $data->links('pagination::tailwind') }}
    </div>
</div>
@endsection
