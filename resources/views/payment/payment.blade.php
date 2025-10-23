@extends('dashboard.layouts.main')

@section('container')
<div class="py-2 px-4">
    @if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <h2 class="text-3xl font-semibold mb-4" style="color: #5f9233;">Daftar Transaksi</h2>

    @if($orders->isEmpty())
        <p class="text-gray-600">Belum ada order yang tersedia.</p>
    @else
        <table class="table-auto w-full border border-gray-300">
            <thead class="bg-lime-600 text-white">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Tanggal Pemesanan</th>
                    <th class="px-4 py-2 border">Biaya</th>
                    <th class="px-4 py-2 border">Status Pembayaran</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border">{{ $order->id }}</td>
                    <td class="px-4 py-2 border">{{ $order->order_date->format('d-m-Y') }}</td>
                    <td class="px-4 py-2 border">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 border">
                        @if($order->payment && $order->payment->payment_status === 'Belum Dibayar')
                            <span class="text-red-600 font-semibold">Belum Dibayar</span>
                        @else
                            <span class="text-green-600 font-semibold">Sudah Dibayar</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 border">
                        @if(!$order->payment || $order->payment->payment_status === 'Belum Dibayar')
                            @if(isset($snapTokens[$order->id]) && $snapTokens[$order->id])
                                <button onclick="payWithSnap('{{ $snapTokens[$order->id] }}')"
                                    class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition">
                                    Bayar
                                </button>
                            @else
                                <span class="text-red-500 text-sm">Token gagal dibuat</span>
                            @endif
                        @else
                            <span class="text-gray-500">Sudah Dibayar</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
        const button = event.target;
        button.disabled = true;
        button.innerText = 'Memuat...';

        snap.pay(token, {
            onSuccess: function(result){
                alert("Pembayaran berhasil!");
                location.reload();
            },
            onPending: function(result){
                alert("Menunggu pembayaran...");
                location.reload();
            },
            onError: function(result){
                alert("Terjadi kesalahan dalam pembayaran.");
                console.log(result);
                button.disabled = false;
                button.innerText = 'Bayar';
            },
            onClose: function(){
                console.log("Popup ditutup tanpa menyelesaikan pembayaran");
                button.disabled = false;
                button.innerText = 'Bayar';
            }
        });
    }
</script>
@endsection
