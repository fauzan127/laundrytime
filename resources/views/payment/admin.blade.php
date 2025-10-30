@extends('dashboard.layouts.main')

@section('container')
<div class="py-6 px-6">
    <h2 class="text-3xl font-bold mb-6" style="color: #5f9233;">Manajemen Transaksi</h2>

    <!-- Filter dan Pencarian -->
    <div class="bg-white p-4 rounded-lg shadow-md mb-6">
        <form action="{{ route('admin.payment') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Pencarian -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Transaksi</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" 
                       placeholder="Cari berdasarkan nama pelanggan">
            </div>
            
            <!-- Filter Status Pembayaran -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Status</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                </select>
            </div>
            
            <!-- Filter Tanggal Mulai -->
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            
            <!-- Filter Tanggal Sampai -->
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            
            <!-- Tombol Aksi -->
            <div class="flex items-end space-x-2 md:col-span-4">
                <button type="submit" class="px-4 py-2 bg-[#5f9233] text-white rounded-md hover:bg-green-700 transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                    Terapkan Filter
                </button>
                <a href="{{ route('admin.payment') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </a>
            </div>
        </form>
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-gray-500 italic text-lg">Belum ada transaksi yang tersedia.</p>
            @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to']))
                <p class="text-gray-400 mt-2">Coba ubah filter pencarian Anda</p>
                <a href="{{ route('admin.payment') }}" class="inline-block mt-4 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    Tampilkan Semua Transaksi
                </a>
            @endif
        </div>
    @else
    <!-- Statistik Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-blue-500">
            <h3 class="text-lg font-semibold text-gray-700">Total Transaksi</h3>
            <p class="text-2xl font-bold text-gray-900">{{ $orders->total() }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-green-500">
            <h3 class="text-lg font-semibold text-gray-700">Sudah Dibayar</h3>
            <p class="text-2xl font-bold text-green-600">{{ $paidCount ?? 0 }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-red-500">
            <h3 class="text-lg font-semibold text-gray-700">Belum Dibayar</h3>
            <p class="text-2xl font-bold text-red-600">{{ $unpaidCount ?? 0 }}</p>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <div class="overflow-x-auto rounded-lg shadow-md">
        <table class="min-w-full bg-white border border-gray-200">
            <thead style="background-color: #5f9233;" class="text-white">
                <tr>
                    <th class="px-6 py-3 text-center text-sm font-semibold border">No</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold border">Nama Pelanggan</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold border">Order Number</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold border">Tanggal Pemesanan</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold border">Tanggal Pembayaran</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold border">Total</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold border">Metode</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold border">Status</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold border">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-center">
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-700">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $order->user->name ?? ($order->customer_name ?? '-') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">ORDER-{{ $order->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $order->order_date->format('d-m-Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ optional($order->payment)->created_at ? $order->payment->created_at->format('d-m-Y H:i') : '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 font-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        @if($order->payment && $order->payment->payment_method)
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                {{ $order->payment->payment_method }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($order->payment && $order->payment->payment_status === 'Sudah Dibayar')
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Sudah Dibayar</span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Belum Dibayar</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex justify-center space-x-2">
                            <!-- Tombol Update Status Pembayaran -->
                            @if($order->payment && $order->payment->payment_status === 'Sudah Dibayar')
                                <button class="text-red-600 hover:text-red-800 transition mark-unpaid-btn" 
                                        data-order-id="{{ $order->id }}"
                                        title="Tandai sebagai Belum Dibayar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"/>
                                </svg>
                                </button>
                            @else
                                <button class="text-green-600 hover:text-green-800 transition mark-paid-btn" 
                                        data-order-id="{{ $order->id }}"
                                        title="Tandai sebagai Sudah Dibayar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex items-center justify-between">
        <div class="text-sm text-gray-700">
            Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari {{ $orders->total() }} transaksi
        </div>
        <div>
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Modal Konfirmasi -->
<div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4" id="modalTitle">Konfirmasi</h3>
        <p class="mb-6" id="modalMessage">Apakah Anda yakin?</p>
        <div class="flex justify-end space-x-3">
            <button id="cancelAction" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                Batal
            </button>
            <button id="confirmAction" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                Konfirmasi
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal konfirmasi
        const confirmationModal = document.getElementById('confirmationModal');
        const cancelAction = document.getElementById('cancelAction');
        const confirmAction = document.getElementById('confirmAction');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        
        let currentOrderId = null;
        let currentAction = null;

        // Fungsi untuk menampilkan modal
        function showModal(title, message, action, orderId) {
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            currentAction = action;
            currentOrderId = orderId;
            confirmationModal.classList.remove('hidden');
        }

        // Mark as paid
        document.querySelectorAll('.mark-paid-btn').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                showModal(
                    'Konfirmasi Pembayaran',
                    'Apakah Anda yakin ingin menandai transaksi ORDER-' + orderId + ' sebagai sudah dibayar?',
                    'mark-paid',
                    orderId
                );
            });
        });

        // Mark as unpaid
        document.querySelectorAll('.mark-unpaid-btn').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                showModal(
                    'Konfirmasi Pembayaran',
                    'Apakah Anda yakin ingin menandai transaksi ORDER-' + orderId + ' sebagai belum dibayar?',
                    'mark-unpaid',
                    orderId
                );
            });
        });

        // Confirm action
        confirmAction.addEventListener('click', function() {
            if (!currentOrderId || !currentAction) return;

            let url = '';
            if (currentAction === 'mark-paid') {
                url = `/admin/payment/${currentOrderId}/mark-paid`;
            } else if (currentAction === 'mark-unpaid') {
                url = `/admin/payment/${currentOrderId}/mark-unpaid`;
            }

            // Tampilkan loading
            confirmAction.innerHTML = 'Memproses...';
            confirmAction.disabled = true;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Refresh halaman setelah sukses
                    location.reload();
                } else {
                    alert('Terjadi kesalahan: ' + data.message);
                    confirmationModal.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui status pembayaran.');
                confirmationModal.classList.add('hidden');
            })
            .finally(() => {
                // Reset tombol
                confirmAction.innerHTML = 'Konfirmasi';
                confirmAction.disabled = false;
            });
        });

        // Cancel action
        cancelAction.addEventListener('click', function() {
            confirmationModal.classList.add('hidden');
            currentOrderId = null;
            currentAction = null;
        });

        // Close modal ketika klik di luar
        confirmationModal.addEventListener('click', function(e) {
            if (e.target === confirmationModal) {
                confirmationModal.classList.add('hidden');
                currentOrderId = null;
                currentAction = null;
            }
        });
    });
</script>
@endsection