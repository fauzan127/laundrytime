@extends('dashboard.layouts.main')

@section('container')
<div class="py-4 px-4">
    {{-- Flash message dengan SweetAlert --}}
    @if(session('success'))
    <div id="success-notification" class="hidden" data-message="{{ session('success') }}"></div>
    @endif

    {{-- Header Section --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6">
        <div class="mb-4 lg:mb-0">
            <h1 id="greeting" class="text-2xl font-bold text-gray-800 mb-1 opacity-0 translate-y-5 transition-all duration-500"></h1>
            <p class="text-gray-600 text-sm">Berikut adalah riwayat pesanan laundry Anda</p>
        </div>
        <a href="{{ route('order.create') }}" 
           class="inline-flex items-center gap-2 bg-[#5f9233] text-white px-4 py-2 rounded-lg hover:bg-[#4a7a29] transition-all duration-200 shadow-sm hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Pesan Laundry Baru
        </a>
    </div>

    {{-- Status Summary Cards - Minimalist Modern --}}
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

        {{-- Total Pesanan --}}
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/60 p-5 hover:shadow-2xl hover:border-purple-300/50 hover:scale-105 transition-all duration-300 group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="flex-1">
                    <p class="text-gray-600 text-sm font-semibold mb-1">Total Pesanan</p>
                    <p class="text-3xl font-bold text-gray-800 group-hover:text-purple-600 transition-colors">{{ $orders->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-500 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-purple-200/50 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-500 flex items-center gap-2">
                    <span class="w-2 h-2 bg-purple-400 rounded-full"></span>
                    All orders
                </p>
                <div class="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded-full font-medium">
                    Total
                </div>
            </div>
        </div>
    </div>

    {{-- Pesanan Terbaru Section --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">3 Pesanan Terbaru</h3>
                    <p class="text-gray-600 text-xs mt-1">Pesanan laundry yang paling baru</p>
                </div>
                @if($orders->count() > 3)
                <a href="{{ route('order.index') }}" 
                   class="inline-flex items-center gap-1 text-[#5f9233] hover:text-[#4a7a29] font-medium transition-colors text-sm">
                    Lihat Semua
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                @endif
            </div>
        </div>
        
        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-200">No</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-200">Tanggal</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-200">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-200">Berat</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-200">Biaya</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-200">Pengantaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders->take(3) as $order)
                    <tr class="hover:bg-gray-50/80 transition-colors duration-150">
                        {{-- No --}}
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                                {{ $loop->iteration }}
                            </span>
                        </td>
                        
                        {{-- Tanggal --}}
                        <td class="px-4 py-3 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-medium text-gray-900">{{ $order->order_date?->format('d M Y') ?? '-' }}</span>
                                <span class="text-xs text-gray-500 mt-0.5">Ambil: {{ $order->pickup_date?->format('d M') ?? '-' }}</span>
                            </div>
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
                                        <span class="text-xs font-semibold text-orange-700">Diantar</span>
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
                        
                        {{-- Berat --}}
                        <td class="px-4 py-3 text-center">
                            <span class="text-sm text-gray-900">{{ $order->weight ?? '0' }} kg</span>
                        </td>
                        
                        {{-- Biaya --}}
                        <td class="px-4 py-3 text-center">
                            <span class="text-sm text-gray-900">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
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
                    </tr>
                    @empty
                    {{-- Empty State --}}
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <p class="text-base font-medium text-gray-500 mb-1">Belum ada pesanan</p>
                                <p class="text-xs text-gray-400">Pesanan laundry Anda akan muncul di sini</p>
                                <a href="{{ route('order.create') }}" 
                                   class="mt-3 inline-flex items-center gap-1 bg-[#5f9233] text-white px-3 py-1.5 rounded text-sm hover:bg-[#4a7a29] transition-colors">
                                    Buat Pesanan Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Greeting Script --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // SweetAlert2 Notifications
        const successNotification = document.getElementById('success-notification');
        if (successNotification) {
            const message = successNotification.getAttribute('data-message');
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#f0f9f0',
                iconColor: '#10b981'
            });
        }

        // Greeting Animation
        let greetingText = "";
        let emoji = "";
        const hour = new Date().getHours();

        if (hour >= 5 && hour < 12) {
            greetingText = "Selamat Pagi";
            emoji = "🌅";
        } else if (hour >= 12 && hour < 15) {
            greetingText = "Selamat Siang";
            emoji = "☀️";
        } else if (hour >= 15 && hour < 18) {
            greetingText = "Selamat Sore";
            emoji = "🌇";
        } else {
            greetingText = "Selamat Malam";
            emoji = "🌙";
        }

        const userName = @json(Auth::user()->name);
        const greetingElement = document.getElementById("greeting");
        greetingElement.innerHTML = `${greetingText}, <span class="text-[#5f9233]">${userName}</span>! ${emoji}`;

        setTimeout(() => {
            greetingElement.classList.remove('opacity-0', 'translate-y-5');
        }, 300);
    });
</script>

<style>
    /* Custom scrollbar */
    .overflow-x-auto::-webkit-scrollbar {
        height: 4px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 2px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 2px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection