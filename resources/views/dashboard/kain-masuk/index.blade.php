@extends('dashboard.layouts.main')

@section('container')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-green-700">Data Kain Masuk</h1>
        <a href="{{ route('kain-masuk.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            + Tambah Data
        </a>
    </div>

    <!-- Notifikasi sukses -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabel Data -->
    <table class="w-full mt-4 border border-gray-200 text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border text-center">No</th>
                <th class="p-2 border">Nama</th>
                <th class="p-2 border">No. HP</th>
                <th class="p-2 border text-right">Total Bayar</th>
                <th class="p-2 border text-center">Status Pembayaran</th>
                <th class="p-2 border text-center">Tanggal Transaksi</th>
                <th class="p-2 border text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                    <td class="p-2 border text-center">{{ $index + 1 }}</td>
                    <td class="p-2 border">{{ $item->customer_name }}</td>
                    <td class="p-2 border">{{ $item->customer_phone }}</td>
                    <td class="p-2 border text-right">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                    <td class="p-2 border text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded 
                            @if($item->status == 'pending') bg-yellow-200 text-yellow-800 
                            @elseif($item->status == 'proses') bg-blue-200 text-blue-800 
                            @elseif($item->status == 'selesai') bg-green-200 text-green-800 
                            @else bg-gray-200 text-gray-700 @endif">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td class="p-2 border text-center">
                        {{ \Carbon\Carbon::parse($item->pickup_date)->format('d/m/Y') }}
                    </td>
                    <td class="p-2 border text-center flex justify-center gap-2">
                        <a href="{{ route('kain-masuk.edit', $item->id) }}" class="text-yellow-600 hover:underline">Edit</a>
                        <form action="{{ route('kain-masuk.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-gray-500 py-6">Belum ada data kain masuk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4 flex justify-center">
        {{ $data->links('pagination::tailwind') }}
    </div>
</div>
@endsection
