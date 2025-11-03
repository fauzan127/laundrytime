@extends('dashboard.layouts.main')

@section('container')
<div class="py-4 px-4">
    {{-- Flash message --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-3 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-4 w-4 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Header Section --}}
    <div class="mb-6">
        <h1 id="greeting" class="text-2xl font-bold text-gray-800 mb-1 opacity-0 translate-y-5 transition-all duration-500"></h1>
        <p class="text-gray-600 text-sm">Kelola dan pantau semua pesanan laundry</p>
    </div>

    {{-- Status Summary Cards - Glassmorphism Design --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        {{-- Diproses --}}
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/60 p-5 hover:shadow-2xl hover:border-yellow-300/50 hover:scale-105 transition-all duration-300 group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="flex-1">
                    <p class="text-gray-600 text-sm font-semibold mb-1">Sedang Diproses</p>
                    <p class="text-3xl font-bold text-gray-800 group-hover:text-yellow-600 transition-colors">{{ $statusCounts['diproses'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-yellow-200/50 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-500 flex items-center gap-2">
                    <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                    In the process
                </p>
                <div class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full font-medium">
                    Active
                </div>
            </div>
        </div>

        {{-- Siap Antar --}}
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/60 p-5 hover:shadow-2xl hover:border-orange-300/50 hover:scale-105 transition-all duration-300 group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="flex-1">
                    <p class="text-gray-600 text-sm font-semibold mb-1">Siap Diantar</p>
                    <p class="text-3xl font-bold text-gray-800 group-hover:text-orange-600 transition-colors">{{ $statusCounts['siap_antar'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-orange-200/50 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-500 flex items-center gap-2">
                    <span class="w-2 h-2 bg-orange-400 rounded-full"></span>
                    Ready to ship
                </p>
                <div class="text-xs text-orange-600 bg-orange-100 px-2 py-1 rounded-full font-medium">
                    Ready
                </div>
            </div>
        </div>

        {{-- Antar --}}
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/60 p-5 hover:shadow-2xl hover:border-blue-300/50 hover:scale-105 transition-all duration-300 group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="flex-1">
                    <p class="text-gray-600 text-sm font-semibold mb-1">Diantar</p>
                    <p class="text-3xl font-bold text-gray-800 group-hover:text-blue-600 transition-colors">{{ $statusCounts['antar'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-blue-200/50 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-500 flex items-center gap-2">
                    <span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
                    On the way
                </p>
                <div class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full font-medium">
                    Moving
                </div>
            </div>
        </div>

        {{-- Selesai --}}
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/60 p-5 hover:shadow-2xl hover:border-green-300/50 hover:scale-105 transition-all duration-300 group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="flex-1">
                    <p class="text-gray-600 text-sm font-semibold mb-1">Selesai</p>
                    <p class="text-3xl font-bold text-gray-800 group-hover:text-green-600 transition-colors">{{ $statusCounts['sampai_tujuan'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-500 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-green-200/50 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-500 flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                    Completed
                </p>
                <div class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full font-medium">
                    Done
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <div class="flex flex-col lg:flex-row gap-3 justify-between items-start lg:items-center">
            {{-- Filter Status --}}
            <div class="flex flex-wrap gap-3 items-center">
                <span class="text-gray-700 font-medium text-sm">Status:</span>
                <form method="GET" action="{{ route('dashboard') }}">
                    <select name="status" onchange="this.form.submit()"
                        class="px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#5F9233] focus:border-transparent text-sm">
                        <option value="">Semua Status</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="siap_antar" {{ request('status') == 'siap_antar' ? 'selected' : '' }}>Siap Antar</option>
                        <option value="antar" {{ request('status') == 'antar' ? 'selected' : '' }}>Antar</option>
                        <option value="sampai_tujuan" {{ request('status') == 'sampai_tujuan' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </form>
            </div>

            {{-- Filter Pengantaran --}}
            <div class="flex flex-wrap gap-3 items-center">
                <span class="text-gray-700 font-medium text-sm">Pengantaran:</span>
                <form method="GET" action="{{ route('dashboard') }}">
                    <select name="delivery_type" onchange="this.form.submit()"
                        class="px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#5F9233] focus:border-transparent text-sm">
                        <option value="">Semua Jenis</option>
                        <option value="pengantaran_pribadi" {{ request('delivery_type') == 'pengantaran_pribadi' ? 'selected' : '' }}>Ambil Sendiri</option>
                        <option value="antar_jemput" {{ request('delivery_type') == 'antar_jemput' ? 'selected' : '' }}>Antar Jemput</option>
                    </select>
                </form>
            </div>

            {{-- Search --}}
            <form method="GET" action="{{ route('dashboard') }}" class="w-full lg:w-64">
                <div class="relative">
                    <input 
                        type="text" 
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama pelanggan..." 
                        class="w-full px-3 py-2 pl-9 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#5F9233] focus:border-transparent text-sm"
                    >
                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Order --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-[#5f9233] text-white">
                    <tr>
                        <th class="px-4 py-3 text-center font-semibold text-xs uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-center font-semibold text-xs uppercase tracking-wider">Nama Pelanggan</th>
                        <th class="px-4 py-3 text-center font-semibold text-xs uppercase tracking-wider">No.HP</th>
                        <th class="px-4 py-3 text-center font-semibold text-xs uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center font-semibold text-xs uppercase tracking-wider">Pengantaran</th>
                        <th class="px-4 py-3 text-center font-semibold text-xs uppercase tracking-wider">Biaya</th>
                        <th class="px-4 py-3 text-center font-semibold text-xs uppercase tracking-wider">Tanggal Pemesanan</th>
                        <th class="px-4 py-3 text-center font-semibold text-xs uppercase tracking-wider">Tanggal Pengantaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition-all duration-150">
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                                {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="font-medium text-gray-900 text-sm">{{ $order->customer_name }}</div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="text-gray-600 text-sm">{{ $order->customer_phone }}</div>
                        </td>
                        {{-- Status --}}
                        <td class="px-4 py-3 text-center">
                            @switch($order->status)
                                @case('diproses')
                                    <div class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-50 border border-yellow-200 rounded-full">
                                        <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full animate-pulse"></div>
                                        <span class="text-xs font-semibold text-yellow-700">Diproses</span>
                                    </div>
                                    @break
                                @case('siap_antar')
                                    <div class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 border border-blue-200 rounded-full">
                                        <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                                        <span class="text-xs font-semibold text-blue-700">Siap Antar</span>
                                    </div>
                                    @break
                                @case('antar')
                                    <div class="inline-flex items-center gap-1 px-2 py-1 bg-orange-50 border border-orange-200 rounded-full">
                                        <div class="w-1.5 h-1.5 bg-orange-500 rounded-full animate-pulse"></div>
                                        <span class="text-xs font-semibold text-orange-700">Antar</span>
                                    </div>
                                    @break
                                @case('sampai_tujuan')
                                    <div class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 border border-green-200 rounded-full">
                                        <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                                        <span class="text-xs font-semibold text-green-700">Selesai</span>
                                    </div>
                                    @break
                                @case('cancelled')
                                    <div class="inline-flex items-center gap-1 px-2 py-1 bg-red-50 border border-red-200 rounded-full">
                                        <div class="w-1.5 h-1.5 bg-red-500 rounded-full"></div>
                                        <span class="text-xs font-semibold text-red-700">Dibatalkan</span>
                                    </div>
                                    @break
                                @default
                                    <div class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 border border-gray-200 rounded-full">
                                        <div class="w-1.5 h-1.5 bg-gray-500 rounded-full"></div>
                                        <span class="text-xs font-semibold text-gray-700">Unknown</span>
                                    </div>
                            @endswitch
                        </td>

                        {{-- Pengantaran --}}
                        <td class="px-4 py-3 text-center">
                            @if($order->delivery_type == 'antar_jemput')
                                <div class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 border border-blue-200 rounded-full">
                                    <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-xs font-semibold text-blue-700">Antar Jemput</span>
                                </div>
                            @else
                                <div class="inline-flex items-center gap-1 px-2 py-1 bg-purple-50 border border-purple-200 rounded-full">
                                    <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="text-xs font-semibold text-purple-700">Ambil Sendiri</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-gray-900 text-sm">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="text-gray-700 font-medium text-sm">{{ $order->order_date ? $order->order_date->format('d M Y') : '-' }}</div>
                            <div class="text-gray-500 text-xs">{{ $order->order_date ? $order->order_date->format('H:i') : '' }}</div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="text-gray-700 font-medium text-sm">{{ $order->pickup_date ? $order->pickup_date->format('d M Y') : '-' }}</div>
                            <div class="text-gray-500 text-xs">{{ $order->pickup_date ? $order->pickup_date->format('H:i') : '' }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-base font-medium text-gray-600 mb-1">Belum ada pesanan</p>
                                <p class="text-gray-400 text-sm">Pesanan akan muncul di sini ketika ada order baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="mt-4 bg-white rounded-lg p-3">
        {{ $orders->onEachSide(1)->links() }}
    </div>
    @endif
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
        greetingElement.innerHTML = `${greetingText}, <span class="text-[#5F9233]">${userName}</span>! ${emoji}`;

        setTimeout(() => {
            greetingElement.classList.remove('opacity-0', 'translate-y-5');
        }, 300);
    });
</script>

<style>
    .pagination {
        display: flex;
        gap: 0.25rem;
        list-style: none;
        padding: 0;
        margin: 0;
        justify-content: center;
    }
    
    .pagination li a,
    .pagination li span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.4rem 0.8rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        color: #4b5563;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 0.75rem;
        min-width: 2rem;
        height: 2rem;
    }
    
    .pagination li.active span {
        background-color: #5F9233;
        border-color: #5F9233;
        color: white;
    }
    
    .pagination li:not(.active) a:hover {
        background-color: #f8faf6;
        border-color: #5F9233;
        color: #5F9233;
    }
    
    .pagination li.disabled span {
        color: #9ca3af;
        background-color: #f3f4f6;
        border-color: #e5e7eb;
    }
    
    .overflow-x-auto::-webkit-scrollbar {
        height: 4px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 2px;
    }
</style>
@endsection