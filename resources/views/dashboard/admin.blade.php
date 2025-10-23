@extends('dashboard.layouts.main')

@section('container')
<div class="py-2 px-4">
    {{-- Flash message --}}
    @if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Greeting --}}
    <h1 id="greeting" class="text-2xl font-bold mb-4 opacity-0 translate-y-5 transition-all duration-500"></h1>

    {{-- Judul --}}
    <h2 class="text-3xl font-semibold mb-4" style="color: #5f9233;">Daftar Pesanan</h2>

    {{-- Status Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Diproses Card --}}
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600 text-sm">Diproses</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $statusCounts['diproses'] ?? 0 }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <span class="material-icons-outlined text-yellow-600">autorenew</span>
                </div>
            </div>
        </div>

        {{-- Siap Antar Card --}}
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600 text-sm">Siap Antar</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $statusCounts['siap_antar'] ?? 0 }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <span class="material-icons-outlined text-blue-600">inventory_2</span>
                </div>
            </div>
        </div>

        {{-- Antar Card --}}
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-orange-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600 text-sm">Antar</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $statusCounts['antar'] ?? 0 }}</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <span class="material-icons-outlined text-orange-600">local_shipping</span>
                </div>
            </div>
        </div>

        {{-- Sampai Tujuan Card --}}
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600 text-sm">Sampai Tujuan</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $statusCounts['sampai_tujuan'] ?? 0 }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <span class="material-icons-outlined text-green-600">check_circle</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="flex flex-wrap justify-between items-center gap-2 mb-4">
        {{-- Filter Status (kiri) --}}
        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center">
            <select name="status" onchange="this.form.submit()"
                class="px-4 py-2 border border-[#5F9233] rounded-lg bg-white text-[#5F9233] focus:outline-none focus:ring-2 focus:ring-[#5F9233]">
                <option value="">Semua Status</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="siap_antar" {{ request('status') == 'siap_antar' ? 'selected' : '' }}>Siap Antar</option>
                <option value="antar" {{ request('status') == 'antar' ? 'selected' : '' }}>Antar</option>
                <option value="sampai_tujuan" {{ request('status') == 'sampai_tujuan' ? 'selected' : '' }}>Sampai Tujuan</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </form>

        {{-- Search Nama Pelanggan (kanan) --}}
        <form method="GET" action="{{ route('dashboard') }}" class="flex w-full sm:w-1/2 md:w-1/3">
            <input 
                type="text" 
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari berdasarkan nama pelanggan" 
                class="flex-1 px-4 py-2 border border-[#5F9233] bg-white rounded-l-lg focus:outline-none focus:ring-2 focus:ring-[#5F9233]"
            >
            <button type="submit" class="bg-[#5F9233] text-white px-4 rounded-r-lg flex items-center justify-center hover:bg-[#4C7A2A] transition">
                <span class="material-icons-outlined text-sm">search</span>
            </button>
        </form>
    </div>

    {{-- Tabel Order --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 rounded-lg">
            <thead style="background-color: #5f9233;" class="text-white">
                <tr>
                    <th class="px-4 py-2 text-center">No</th>
                    <th class="px-4 py-2 text-center">Nama Pelanggan</th>
                    <th class="px-4 py-2 text-center">No.HP</th>
                    <th class="px-4 py-2 text-center">Status</th>
                    <th class="px-4 py-2 text-center">Pengantaran</th>
                    <th class="px-4 py-2 text-center">Biaya</th>
                    <th class="px-4 py-2 text-center">Tanggal Pemesanan</th>
                    <th class="px-4 py-2 text-center">Tanggal Pengantaran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr class="border-t hover:bg-[#f0f8ea] transition">
                    <td class="px-4 py-2 text-center">
                        {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-4 py-2">{{ $order->customer_name }}</td>
                    <td class="px-4 py-2">{{ $order->customer_phone }}</td>
                    <td class="px-4 py-2 text-center">
                        @switch($order->status)
                            @case('diproses')
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm">Diproses</span>
                                @break
                            @case('siap_antar')
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">Siap Antar</span>
                                @break
                            @case('antar')
                                <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-sm">Antar</span>
                                @break
                            @case('sampai_tujuan')
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">Sampai Tujuan</span>
                                @break
                            @case('cancelled')
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-sm">Dibatalkan</span>
                                @break
                            @default
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-sm">{{ ucfirst($order->status) }}</span>
                        @endswitch
                    </td>
                    <td class="px-4 py-2 text-center">
                        @switch($order->delivery_type)
                            @case('antar_jemput')
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">Antar Jemput</span>
                                @break
                            @case('pengantaran_pribadi')
                                <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-sm">Pengantaran Pribadi</span>
                                @break
                            @default
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-sm">
                                    {{ ucfirst(str_replace('_', ' ', $order->delivery_type)) }}
                                </span>
                        @endswitch
                    </td>
                    <td class="px-4 py-2 text-right">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 text-center">{{ $order->order_date->format('d M Y') }}</td>
                    <td class="px-4 py-2 text-center">{{ $order->pickup_date->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-gray-500">Belum ada order</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>

{{-- Greeting Script --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let greetingText = "";
        let emoji = "";
        const hour = new Date().getHours();

        if (hour >= 5 && hour < 12) {
            greetingText = "Selamat Pagi";
            emoji = "🌅";
        } else if (hour >= 12 && hour < 15) {
            greetingText = "Selamat Siang";
            emoji = "🌞";
        } else if (hour >= 15 && hour < 18) {
            greetingText = "Selamat Sore";
            emoji = "🌇";
        } else {
            greetingText = "Selamat Malam";
            emoji = "🌙";
        }

        const userName = @json(Auth::user()->name);
        const greetingElement = document.getElementById("greeting");
        greetingElement.innerHTML = `${greetingText}, ${userName}! ${emoji}`;

        setTimeout(() => {
            greetingElement.classList.remove('opacity-0', 'translate-y-5');
        }, 300);
    });
</script>
@endsection