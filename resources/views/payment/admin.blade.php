@extends('dashboard.layouts.main')

@section('container')
<div class="py-4 px-4">
    <h2 class="text-2xl font-bold mb-4 text-[#5f9233]">Manajemen Transaksi</h2>

    <!-- Filter dan Pencarian -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 mb-4">
        <form action="{{ route('admin.payment') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <!-- Pencarian -->
            <div>
                <label for="search" class="block text-xs font-medium text-gray-700 mb-1">Cari Transaksi</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#5F9233] text-sm" 
                       placeholder="Cari nama pelanggan">
            </div>
            
            <!-- Filter Status Pembayaran -->
            <div>
                <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#5F9233] text-sm">
                    <option value="">Semua Status</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                </select>
            </div>
            
            <!-- Filter Tanggal Mulai -->
            <div>
                <label for="date_from" class="block text-xs font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#5F9233] text-sm">
            </div>
            
            <!-- Filter Tanggal Sampai -->
            <div>
                <label for="date_to" class="block text-xs font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#5F9233] text-sm">
            </div>
            
            <!-- Tombol Aksi -->
            <div class="flex items-end space-x-2 md:col-span-4">
                <button type="submit" class="px-3 py-2 bg-[#5f9233] text-white rounded-lg hover:bg-[#4a7a29] transition flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                    Terapkan Filter
                </button>
                <a href="{{ route('admin.payment') }}" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </a>
            </div>
        </form>
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-gray-500 text-sm">Belum ada transaksi yang tersedia.</p>
            @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to']))
                <p class="text-gray-400 text-xs mt-1">Coba ubah filter pencarian Anda</p>
                <a href="{{ route('admin.payment') }}" class="inline-block mt-3 px-3 py-1.5 bg-[#5f9233] text-white rounded-lg hover:bg-[#4a7a29] transition text-sm">
                    Tampilkan Semua Transaksi
                </a>
            @endif
        </div>
    @else
    <!-- Statistik Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Total Transaksi -->
        <div class="bg-gradient-to-br from-blue-50 to-white p-5 rounded-xl shadow-sm border border-blue-100 hover:shadow-lg hover:scale-105 hover:border-blue-300 transition-all duration-300 group cursor-pointer">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">Total Transaksi</h3>
                    <p class="text-2xl font-bold text-gray-800 group-hover:text-blue-700 transition-colors">{{ $orders->total() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Semua pesanan</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-blue-600">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                </svg>
                Semua status
            </div>
        </div>

        <!-- Sudah Dibayar -->
        <div class="bg-gradient-to-br from-green-50 to-white p-5 rounded-xl shadow-sm border border-green-100 hover:shadow-lg hover:scale-105 hover:border-green-300 transition-all duration-300 group cursor-pointer">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">Sudah Dibayar</h3>
                    <p class="text-2xl font-bold text-gray-800 group-hover:text-green-700 transition-colors">{{ $paidCount ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Lunas</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-green-600">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Pembayaran diterima
            </div>
        </div>

        <!-- Belum Dibayar -->
        <div class="bg-gradient-to-br from-red-50 to-white p-5 rounded-xl shadow-sm border border-red-100 hover:shadow-lg hover:scale-105 hover:border-red-300 transition-all duration-300 group cursor-pointer">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-gray-600 mb-1">Belum Dibayar</h3>
                    <p class="text-2xl font-bold text-gray-800 group-hover:text-red-700 transition-colors">{{ $unpaidCount ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Pending</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-red-600">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                Menunggu pembayaran
            </div>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-[#5f9233] text-white">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white/20">No</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white/20">Nama Pelanggan</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white/20">Order ID</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white/20">Tanggal Pemesanan</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white/20">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white/20">Metode</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white/20">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-4 py-3 text-center text-sm text-gray-700">
                            <span class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                                {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-700">{{ $order->customer_name }}</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-700 font-medium">#{{ $order->id }}</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-700">
                            <div class="flex flex-col">
                                <span class="text-xs">{{ $order->order_date->format('d M Y') }}</span>
                                <span class="text-xs text-gray-500">{{ $order->order_date->format('H:i') }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center text-sm font-semibold text-gray-900">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($order->payment && $order->payment->payment_method)
                                <span class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium border border-blue-200">
                                    {{ $order->payment->payment_method }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($order->payment && $order->payment->payment_status === 'Sudah Dibayar')
                                <span class="inline-flex items-center px-2 py-1 bg-green-50 text-green-700 rounded-full text-xs font-semibold border border-green-200">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                                    Sudah Dibayar
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 bg-red-50 text-red-700 rounded-full text-xs font-semibold border border-red-200">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1"></span>
                                    Belum Dibayar
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center space-x-1">
                                <!-- Tombol Edit dengan Icon Pensil -->
                                <button class="edit-payment-btn p-1.5 text-gray-600 hover:text-[#5f9233] hover:bg-gray-100 rounded transition-all duration-200 border border-transparent hover:border-gray-300" 
                                        data-order-id="{{ $order->id }}"
                                        title="Edit Status Pembayaran">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
    <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        {{ $orders->onEachSide(1)->links() }}
    </div>
    @endif
    @endif
</div>

<!-- Modal Edit Status -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-5 w-full max-w-sm mx-4">
        <div class="flex items-center mb-3">
            <div class="w-8 h-8 bg-[#5f9233] rounded-lg flex items-center justify-center mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800" id="editModalTitle">Edit Status Pembayaran</h3>
        </div>
        
        <p class="mb-4 text-sm text-gray-600" id="editModalMessage">Pilih status pembayaran untuk transaksi:</p>
        
        <div class="mb-4">
            <label class="flex items-center mb-2">
                <input type="radio" name="payment_status" value="paid" class="mr-2 text-[#5f9233] focus:ring-[#5f9233]">
                <span class="text-sm text-gray-700">Sudah Dibayar</span>
            </label>
            <label class="flex items-center">
                <input type="radio" name="payment_status" value="unpaid" class="mr-2 text-[#5f9233] focus:ring-[#5f9233]">
                <span class="text-sm text-gray-700">Belum Dibayar</span>
            </label>
        </div>
        
        <div class="flex justify-end space-x-2">
            <button id="cancelEdit" class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded text-sm hover:bg-gray-300 transition border border-gray-300">
                Batal
            </button>
            <button id="saveEdit" class="px-3 py-1.5 bg-[#5f9233] text-white rounded text-sm hover:bg-[#4a7a29] transition border border-[#5f9233]">
                Simpan
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editModal = document.getElementById('editModal');
        const cancelEdit = document.getElementById('cancelEdit');
        const saveEdit = document.getElementById('saveEdit');
        const editModalTitle = document.getElementById('editModalTitle');
        const editModalMessage = document.getElementById('editModalMessage');
        
        let currentOrderId = null;

        // Tombol edit diklik
        document.querySelectorAll('.edit-payment-btn').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                currentOrderId = orderId;
                
                editModalTitle.textContent = 'Edit Status Pembayaran';
                editModalMessage.textContent = 'Pilih status pembayaran untuk transaksi #' + orderId + ':';
                
                // Reset radio buttons
                document.querySelectorAll('input[name="payment_status"]').forEach(radio => {
                    radio.checked = false;
                });
                
                editModal.classList.remove('hidden');
            });
        });

        // Simpan perubahan
        saveEdit.addEventListener('click', function() {
            if (!currentOrderId) return;

            const selectedStatus = document.querySelector('input[name="payment_status"]:checked');
            if (!selectedStatus) {
                alert('Pilih status pembayaran terlebih dahulu!');
                return;
            }

            let url = '';
            if (selectedStatus.value === 'paid') {
                url = `/admin/payment/${currentOrderId}/mark-paid`;
            } else if (selectedStatus.value === 'unpaid') {
                url = `/admin/payment/${currentOrderId}/mark-unpaid`;
            }

            // Tampilkan loading
            const originalText = saveEdit.innerHTML;
            saveEdit.innerHTML = 'Menyimpan...';
            saveEdit.disabled = true;

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
                    location.reload();
                } else {
                    alert('Terjadi kesalahan: ' + data.message);
                    editModal.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui status pembayaran.');
                editModal.classList.add('hidden');
            })
            .finally(() => {
                saveEdit.innerHTML = originalText;
                saveEdit.disabled = false;
            });
        });

        // Batal edit
        cancelEdit.addEventListener('click', function() {
            editModal.classList.add('hidden');
            currentOrderId = null;
        });

        // Close modal ketika klik di luar
        editModal.addEventListener('click', function(e) {
            if (e.target === editModal) {
                editModal.classList.add('hidden');
                currentOrderId = null;
            }
        });
    });
</script>
@endsection