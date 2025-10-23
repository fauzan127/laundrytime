@extends('dashboard.layouts.main')

@section('container')
<div class="py-6 px-4">
    {{-- Flash message --}}
    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Greeting --}}
    <h1 id="greeting" class="text-2xl font-bold mb-2 opacity-0 translate-y-5 transition-all duration-500"></h1>
    <p class="text-gray-600 mb-8">Berikut adalah riwayat pesanan laundry Anda</p>

    {{-- Status Summary --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        {{-- Diproses --}}
        <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
            <div class="bg-yellow-50 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                <span class="material-icons-outlined text-yellow-600">autorenew</span>
            </div>
            <p class="text-gray-500 text-sm">Diproses</p>
            <p class="text-xl font-bold text-gray-800">{{ $statusCounts['diproses'] ?? 0 }}</p>
        </div>

        {{-- Siap Antar --}}
        <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
            <div class="bg-blue-50 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                <span class="material-icons-outlined text-blue-600">inventory_2</span>
            </div>
            <p class="text-gray-500 text-sm">Siap Antar</p>
            <p class="text-xl font-bold text-gray-800">{{ $statusCounts['siap_antar'] ?? 0 }}</p>
        </div>

        {{-- Sampai Tujuan --}}
        <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
            <div class="bg-green-50 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                <span class="material-icons-outlined text-green-600">check_circle</span>
            </div>
            <p class="text-gray-500 text-sm">Selesai</p>
            <p class="text-xl font-bold text-gray-800">{{ $statusCounts['sampai_tujuan'] ?? 0 }}</p>
        </div>

        {{-- Total --}}
        <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
            <div class="bg-gray-50 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                <span class="material-icons-outlined text-gray-600">local_laundry_service</span>
            </div>
            <p class="text-gray-500 text-sm">Total</p>
            <p class="text-xl font-bold text-gray-800">{{ $orders->total() }}</p>
        </div>
    </div>

    {{-- Pesanan Terbaru --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Pesanan Terbaru</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Biaya</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengantaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div>{{ $order->order_date->format('d M Y') }}</div>
                            <div class="text-xs text-gray-400">Ambil: {{ $order->pickup_date->format('d M') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($order->status)
                                @case('diproses')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Diproses</span>
                                    @break
                                @case('siap_antar')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Siap Antar</span>
                                    @break
                                @case('antar')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Antar</span>
                                    @break
                                @case('sampai_tujuan')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                                    @break
                                @case('cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Dibatalkan</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->weight }} kg</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($order->delivery_type == 'antar_jemput')
                                <span class="text-blue-600">Antar Jemput</span>
                            @else
                                <span class="text-purple-600">Ambil Sendiri</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <span class="material-icons-outlined text-gray-400 text-4xl mb-2">local_laundry_service</span>
                                <p class="text-gray-500">Belum ada pesanan</p>
                                <p class="text-sm text-gray-400 mt-1">Pesanan laundry Anda akan muncul di sini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
        @endif
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