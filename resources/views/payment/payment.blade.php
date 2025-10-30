@extends('dashboard.layouts.main')

@section('container')
<div class="py-6 px-6">
    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-sm" role="alert">
        <span class="block sm:inline font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <h2 class="text-3xl font-bold mb-6" style="color: #5f9233;">Daftar Transaksi</h2>

    @if($orders->isEmpty())
    
        <p class="text-gray-500 italic">Belum ada order yang tersedia.</p>
    @else
    <div class="overflow-x-auto rounded-lg shadow-md">
        <table class="min-w-full bg-white border border-gray-200">
           <thead style="background-color: #5f9233;" class="text-white">
                <tr>
                    <th class="px-6 py-3 text-center text-sm font-semibold border">No</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold border">Tanggal Pemesanan</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold border">Biaya</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold border">Status</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold border">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-center">
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $order->order_date->format('d-m-Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm">
                        @if($order->payment && $order->payment->payment_status === 'Belum Dibayar')
                            <span class="inline-flex items-center gap-1 text-red-600 font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Belum Dibayar
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-green-600 font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Sudah Dibayar
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if(!$order->payment || $order->payment->payment_status === 'Belum Dibayar')
                            @if(isset($snapTokens[$order->id]) && $snapTokens[$order->id])
                                <button onclick="payWithSnap('{{ $snapTokens[$order->id] }}')"
                                    class="inline-flex items-center gap-2 bg-lime-600 text-white px-4 py-2 rounded-md hover:bg-lime-700 transition">
                                    Bayar Sekarang
                                </button>
                            @else
                                <span class="text-red-500 text-sm italic">Token gagal dibuat</span>
                            @endif
                        @else
                            <span class="text-gray-400 italic">Sudah Dibayar</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
    @endif
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script
    type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
</script>
<script type="text/javascript">
    function payWithSnap(token) {
        const button = event.target.closest('button');
        button.disabled = true;
        button.innerHTML = 'Memuat...';

        snap.pay(token, {
            onSuccess: function(result){
                alert("✅ Pembayaran berhasil!");
                location.reload();
            },
            onPending: function(result){
                alert("⏳ Menunggu pembayaran...");
                location.reload();
            },
            onError: function(result){
                alert("❌ Terjadi kesalahan dalam pembayaran.");
                console.log(result);
                button.disabled = false;
                button.innerHTML = 'Bayar Sekarang';
            },
            onClose: function(){
                console.log("Popup ditutup tanpa menyelesaikan pembayaran");
                button.disabled = false;
                button.innerHTML = 'Bayar Sekarang';
            }
        });
    }
</script>
@endsection