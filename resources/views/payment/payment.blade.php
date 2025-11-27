@extends('dashboard.layouts.main')
<title>Pembayaran</title>

@section('container')
<!-- Add no-cache meta tags -->
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

<div class="py-4 px-4">
    <!-- SweetAlert2 Notifications -->
    @if(session('success'))
    <div id="success-notification" class="hidden" data-message="{{ session('success') }}"></div>
    @endif

    @if(session('error'))
    <div id="error-notification" class="hidden" data-message="{{ session('error') }}"></div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div class="mb-3 sm:mb-0">
            <h2 class="text-2xl font-bold text-[#5f9233]">Daftar Transaksi</h2>
            <p class="text-gray-600 mt-1 text-sm">Kelola dan pantau semua transaksi laundry Anda</p>
        </div>
        <div class="flex items-center space-x-2 text-xs text-gray-600 bg-gray-50 px-3 py-1.5 rounded-lg">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
            </svg>
            <span>Total: {{ $orders->total() }} transaksi</span>
        </div>
    </div>

    @if($orders->isEmpty())
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
        <div class="max-w-md mx-auto">
            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum ada transaksi</h3>
            <p class="text-gray-500 mb-4 text-sm">Transaksi laundry Anda akan muncul di sini</p>
            <a href="{{ route('order.create') }}" class="inline-flex items-center gap-2 bg-[#5f9233] text-white px-4 py-2 rounded-lg hover:bg-[#4a7a29] transition-all duration-200 shadow-md hover:shadow-lg text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Buat Pesanan Baru
            </a>
        </div>
    </div>
    @else
    <!-- Transactions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gradient-to-r from-[#5f9233] to-[#6ba83a]">
                    <tr>
                        <th class="px-4 py-3 text-center font-semibold text-white border-r border-white/20">NO</th>
                        <th class="px-4 py-3 text-center font-semibold text-white border-r border-white/20">
                            <div class="flex items-center justify-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>TANGGAL</span>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center font-semibold text-white border-r border-white/20">BIAYA</th>
                        <th class="px-4 py-3 text-center font-semibold text-white border-r border-white/20">STATUS</th>
                        <th class="px-4 py-3 text-center font-semibold text-white">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="orders-table-body">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50/80 transition-all duration-200 group" id="order-{{ $order->id }}">
                        <!-- No -->
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs font-medium text-gray-700 bg-gray-100 px-2 py-1 rounded-full">
                                {{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}
                            </span>
                        </td>
                        
                        <!-- Tanggal -->
                        <td class="px-4 py-3 text-center">
                            <div class="flex flex-col items-center justify-center gap-0.5">
                                <div class="font-semibold text-gray-900">{{ $order->order_date->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $order->order_date->format('H:i') }} WIB</div>
                            </div>
                        </td>
                        
                        <!-- Biaya -->
                        <td class="px-4 py-3 text-center">
                            <span class="font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </td>
                        
                        <!-- Status -->
                        <td class="px-4 py-3 text-center">
                            @php
                                $currentPaymentStatus = $order->payment ? $order->payment->payment_status : 'Belum Dibayar';
                            @endphp

                            @if($currentPaymentStatus === 'Belum Dibayar')
                                <div class="inline-flex items-center justify-center gap-1 px-2 py-1 bg-red-50 border border-red-200 rounded-full text-xs">
                                    <div class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></div>
                                    <span class="font-semibold text-red-700">Belum Bayar</span>
                                </div>
                            @elseif($currentPaymentStatus === 'Menunggu Pembayaran')
                                <div class="inline-flex items-center justify-center gap-1 px-2 py-1 bg-yellow-50 border border-yellow-200 rounded-full text-xs">
                                    <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full animate-pulse"></div>
                                    <span class="font-semibold text-yellow-700">Menunggu Pembayaran</span>
                                </div>
                            @elseif($currentPaymentStatus === 'Sudah Dibayar')
                                <div class="inline-flex items-center justify-center gap-1 px-2 py-1 bg-green-50 border border-green-200 rounded-full text-xs">
                                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                                    <span class="font-semibold text-green-700">Sudah Bayar</span>
                                </div>
                            @else
                                <span class="text-xs text-gray-500">Status Tidak Dikenal</span>
                            @endif
                        </td>
                        
                        <!-- Aksi -->
                        <td class="px-4 py-3 text-center">
                            @if($order->weight > 0 && $order->weight != 1.0)
                                @php
                                    $status = $order->payment->payment_status ?? 'Belum Dibayar';
                                @endphp
                                @if(in_array($status, ['Belum Dibayar', 'Menunggu Pembayaran']))
                                    @if(isset($snapTokens[$order->id]) && $snapTokens[$order->id])
                                        <button onclick="payWithSnap('{{ $snapTokens[$order->id] }}', {{ $order->id }})"
                                            class="pay-button inline-flex items-center justify-center gap-1 bg-gradient-to-r from-[#5f9233] to-[#6ba83a] text-white px-3 py-1.5 rounded-lg hover:from-[#4a7a29] hover:to-[#5f9233] transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 group mx-auto text-xs">
                                            <svg class="w-3 h-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            <span class="font-medium">Bayar</span>
                                        </button>
                                    @else
                                        <span class="inline-flex items-center justify-center gap-1 px-2 py-1 bg-red-50 text-red-700 rounded-lg text-xs mx-auto">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Token Error
                                        </span>
                                    @endif
                                @elseif($status === 'Sudah Dibayar')
                                    <span class="inline-flex items-center justify-center gap-1 px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs mx-auto">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center gap-1 px-2 py-1 bg-gray-50 text-gray-500 rounded-lg text-xs mx-auto">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Status Tidak Dikenal
                                    </span>
                                @endif
                            @elseif($order->weight == 1.0)
                                {{-- TAMPILAN JIKA WEIGHT = 1.0 --}}
                                <span class="inline-flex items-center justify-center gap-1 px-2 py-1 bg-gray-100 text-gray-500 rounded-lg text-xs mx-auto cursor-not-allowed">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Menunggu Konfirmasi Admin
                                </span>
                            @else
                                {{-- TAMPILAN JIKA WEIGHT = 0 --}}
                                <span class="inline-flex items-center justify-center gap-1 px-2 py-1 bg-yellow-50 text-yellow-700 rounded-lg text-xs mx-auto">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Menunggu Konfirmasi Admin
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-sm">
        {{ $orders->onEachSide(1)->links() }}
    </div>
    @endif
    @endif
</div>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.clientKey') }}"></script>

<script type="text/javascript">
    // SweetAlert2 Notifications
    document.addEventListener('DOMContentLoaded', function() {
        // Success notification
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

        // Error notification
        const errorNotification = document.getElementById('error-notification');
        if (errorNotification) {
            const message = errorNotification.getAttribute('data-message');
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                background: '#fef2f2',
                iconColor: '#ef4444'
            });
        }

        // Remove default Laravel pagination info if exists
        const defaultPaginationInfo = document.querySelector('.flex.justify-between.flex-1');
        if (defaultPaginationInfo) {
            defaultPaginationInfo.remove();
        }

        // Start auto-refresh for unpaid orders
        startAutoRefresh();
    });

    // Real-time status check setiap 10 detik untuk order belum bayar
    function startAutoRefresh() {
        setInterval(() => {
            const unpaidOrders = document.querySelectorAll('.payment-status-unpaid');
            if (unpaidOrders.length > 0) {
                console.log('🔄 Checking payment status updates...');
                
                // AJAX request untuk check status terbaru
                fetch('{{ route("payment.checkStatus") }}?t=' + new Date().getTime(), {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Cache-Control': 'no-cache'
                    },
                    cache: 'no-cache'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.updated) {
                        console.log('💰 Payment status updated, reloading...');
                        location.reload();
                    }
                })
                .catch(error => {
                    console.log('❌ Error checking status:', error);
                });
            }
        }, 10000);
    }

    // Payment function with enhanced UI
    function payWithSnap(token, orderId) {
        const button = event.target.closest('button');
        const originalContent = button.innerHTML;
        
        // Show loading state
        button.disabled = true;
        button.innerHTML = `
            <div class="flex items-center gap-1 text-xs">
                <div class="w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                <span>Loading...</span>
            </div>
        `;

        // Add loading overlay to table
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        loadingOverlay.innerHTML = `
            <div class="bg-white rounded-xl p-4 max-w-sm mx-4 text-center">
                <div class="w-12 h-12 mx-auto mb-3 border-4 border-[#5f9233] border-t-transparent rounded-full animate-spin"></div>
                <h3 class="text-md font-semibold text-gray-800 mb-1">Mempersiapkan Pembayaran</h3>
                <p class="text-gray-600 text-sm">Sedang memuat halaman pembayaran...</p>
            </div>
        `;
        document.body.appendChild(loadingOverlay);

        snap.pay(token, {
            onSuccess: function(result){
                document.body.removeChild(loadingOverlay);
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Berhasil!',
                    html: `
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-3 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700 mb-1 text-sm">Transaksi #${orderId} berhasil dibayar</p>
                            <p class="text-xs text-gray-500">Halaman akan dimuat ulang...</p>
                        </div>
                    `,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    willClose: () => {
                        window.location.href = window.location.href.split('?')[0] + '?t=' + new Date().getTime();
                    }
                });
            },
            onPending: function(result){
                document.body.removeChild(loadingOverlay);
                Swal.fire({
                    icon: 'info',
                    title: 'Menunggu Pembayaran',
                    html: `
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-3 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700 mb-1 text-sm">Pembayaran Anda sedang diproses</p>
                            <p class="text-xs text-gray-500">Silahkan lakukan pembayaran</p>
                        </div>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#5f9233'
                }).then(() => {
                    setTimeout(() => {
                        location.reload();
                    }, 5000);
                });
            },
            onError: function(result){
                document.body.removeChild(loadingOverlay);
                button.disabled = false;
                button.innerHTML = originalContent;
                
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Gagal',
                    html: `
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-3 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700 mb-1 text-sm">Terjadi kesalahan dalam pembayaran</p>
                            <p class="text-xs text-gray-500">Silakan coba lagi atau hubungi customer service</p>
                        </div>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: 'Coba Lagi',
                    confirmButtonColor: '#5f9233'
                });
            },
            onClose: function(){
                document.body.removeChild(loadingOverlay);
                button.disabled = false;
                button.innerHTML = originalContent;
                
                Swal.fire({
                    icon: 'info',
                    title: 'Pembayaran Dibatalkan',
                    text: 'Anda menutup halaman pembayaran',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: '#fff3cd',
                    iconColor: '#ffc107'
                });
            }
        });
    }

    // Add hover effects to table rows
    document.addEventListener('DOMContentLoaded', function() {
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(2px)';
            });
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
    });

    // Manual refresh button functionality (optional)
    function manualRefresh() {
        Swal.fire({
            title: 'Memperbarui Data',
            text: 'Sedang memuat data terbaru...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        setTimeout(() => {
            location.reload();
        }, 1000);
    }

    // Keyboard shortcut untuk refresh (F5 atau Ctrl+R)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
            e.preventDefault();
            manualRefresh();
        }
    });
</script>

<style>
    /* Custom scrollbar for table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Smooth transitions */
    .pay-button {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Style default Laravel pagination */
    .pagination {
        display: flex;
        gap: 0.25rem;
        list-style: none;
        padding: 0;
        margin: 0;
        justify-content: center;
        font-size: 0.75rem;
    }
    
    .pagination li {
        display: inline-block;
    }
    
    .pagination li a,
    .pagination li span {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        color: #4b5563;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .pagination li.active span {
        background-color: #5f9233;
        border-color: #5f9233;
        color: white;
    }
    
    .pagination li:not(.active) a:hover {
        background-color: #f8fafc;
        border-color: #5f9233;
        color: #5f9233;
    }
    
    .pagination li.disabled span {
        color: #9ca3af;
        background-color: #f3f4f6;
        border-color: #e5e7eb;
        cursor: not-allowed;
    }
    
    /* Hide default Laravel pagination info */
    .flex.justify-between.flex-1 {
        display: none !important;
    }
    
    /* Ensure pagination is centered on mobile */
    @media (max-width: 640px) {
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
    }
    
    /* Animation for status update */
    .payment-status-unpaid {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.8; }
        100% { opacity: 1; }
    }
</style>
@endsection