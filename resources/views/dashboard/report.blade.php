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
    <h2 class="text-3xl font-semibold mb-4" style="color: #5f9233;">Laporan Penjualan</h2>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        {{-- Total Revenue Card --}}
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600 text-sm">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-800">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <span class="material-icons-outlined text-green-600">attach_money</span>
                </div>
            </div>
        </div>

        {{-- Total Transactions Card --}}
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600 text-sm">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalTransactions }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <span class="material-icons-outlined text-blue-600">receipt</span>
                </div>
            </div>
        </div>

        {{-- Average Revenue Card --}}
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600 text-sm">Rata-rata Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-800">
                        Rp{{ $totalTransactions > 0 ? number_format($totalRevenue / $totalTransactions, 0, ',', '.') : '0' }}
                    </p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <span class="material-icons-outlined text-purple-600">trending_up</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Period --}}
    <div class="flex flex-wrap justify-between items-center gap-2 mb-4">
        {{-- Period Filter --}}
        <form method="GET" action="{{ route('dashboard.report') }}" class="flex items-center">
            <select name="period" onchange="this.form.submit()"
                class="px-4 py-2 border border-[#5F9233] rounded-lg bg-white text-[#5F9233] focus:outline-none focus:ring-2 focus:ring-[#5F9233]">
                <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Harian</option>
            </select>
        </form>
    </div>

    {{-- Report Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 rounded-lg">
            <thead style="background-color: #5f9233;" class="text-white">
                <tr>
                    <th class="px-4 py-2 text-center">No</th>
                    <th class="px-4 py-2 text-center">Periode</th>
                    <th class="px-4 py-2 text-center">Jumlah Transaksi</th>
                    <th class="px-4 py-2 text-center">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salesData as $data)
                <tr class="border-t hover:bg-[#f0f8ea] transition">
                    <td class="px-4 py-2 text-center">
                        {{ $loop->iteration }}
                    </td>
                    <td class="px-4 py-2 text-center font-medium">{{ $data['period'] }}</td>
                    <td class="px-4 py-2 text-center">{{ $data['transactions'] }}</td>
                    <td class="px-4 py-2 text-right font-medium">Rp{{ number_format($data['revenue'], 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-8 text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <span class="material-icons-outlined text-gray-400 text-4xl mb-2">analytics</span>
                            <p class="text-gray-500">Belum ada data penjualan</p>
                            <p class="text-sm text-gray-400 mt-1">Data penjualan akan muncul di sini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
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
