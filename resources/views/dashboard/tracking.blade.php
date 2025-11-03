@extends('dashboard.layouts.main')

@section('container')
<div class="py-4 px-4">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div class="w-full lg:w-auto">
            <h2 class="text-xl md:text-2xl font-bold text-[#5f9233] mb-2">Manajemen Pengelolaan Status</h2>
        </div>
    </div>

    <!-- Search dan Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
            <!-- Search dan Filter Controls -->
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-2/3">
                <!-- Search -->
                <div class="relative flex-1">
                    <input type="text" id="searchInput" placeholder="Cari nama pelanggan..." 
                           class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5F9233] focus:border-[#5F9233] text-sm">
                    <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                
                <!-- Filter Status -->
                <select id="statusFilter" class="w-full sm:w-48 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5F9233] focus:border-[#5F9233] text-sm bg-white">
                    <option value="">Semua Status</option>
                    <option value="diproses">Diproses</option>
                    <option value="siap_antar">Siap Diantar</option>
                    <option value="antar">Diantar</option>
                </select>
                
                <!-- Reset Filter -->
                <button id="resetFilter" class="w-full sm:w-auto px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium whitespace-nowrap">
                    Reset Filter
                </button>
            </div>

            <!-- Result Count -->
            <div id="filterInfo" class="hidden lg:block">
                <div class="bg-blue-50 border border-blue-200 rounded-lg px-3 py-2">
                    <span id="resultCount" class="text-sm text-blue-700 font-medium"></span>
                </div>
            </div>
        </div>

        <!-- Mobile Result Count -->
        <div id="mobileFilterInfo" class="mt-3 lg:hidden hidden">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <span id="mobileResultCount" class="text-sm text-blue-700 font-medium"></span>
            </div>
        </div>
    </div>

    <!-- Stats Cards dengan Glow Hover Effects -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card Diproses -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 transition-all duration-300 hover:shadow-xl hover:scale-105 hover:-translate-y-1 hover:border-orange-400 hover:shadow-yellow-100 group cursor-pointer">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full group-hover:animate-pulse"></div>
                        <div class="text-lg font-semibold text-yellow-700">Sedang Diproses</div>
                    </div>
                    <div class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $orders->where('status', 'diproses')->count() }}
                    </div>
                </div>
                <div class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full text-xs font-medium group-hover:bg-yellow-200 group-hover:scale-110 transition-all">
                    {{ $orders->count() > 0 ? round(($orders->where('status', 'diproses')->count() / $orders->count()) * 100, 1) : 0 }}%
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                <div class="bg-yellow-600 h-2 rounded-full transition-all duration-700 group-hover:bg-gradient-to-r from-yellow-500 to-yellow-400" 
                    style="width: {{ $orders->count() > 0 ? ($orders->where('status', 'diproses')->count() / $orders->count()) * 100 : 0 }}%"></div>
            </div>
        </div>

        <!-- Card Siap Diantar -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 transition-all duration-300 hover:shadow-xl hover:scale-105 hover:-translate-y-1 hover:border-orange-400 hover:shadow-orange-100 group cursor-pointer">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-orange-500 rounded-full group-hover:animate-pulse"></div>
                        <div class="text-lg font-semibold text-orange-700">Siap Diantar</div>
                    </div>
                    <div class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $orders->where('status', 'siap_antar')->count() }}
                    </div>
                </div>
                <div class="bg-orange-100 text-orange-600 px-2 py-1 rounded-full text-xs font-medium group-hover:bg-orange-200 group-hover:scale-110 transition-all">
                    {{ $orders->count() > 0 ? round(($orders->where('status', 'siap_antar')->count() / $orders->count()) * 100, 1) : 0 }}%
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                <div class="bg-orange-600 h-2 rounded-full transition-all duration-700 group-hover:bg-gradient-to-r from-orange-500 to-orange-400" 
                    style="width: {{ $orders->count() > 0 ? ($orders->where('status', 'siap_antar')->count() / $orders->count()) * 100 : 0 }}%"></div>
            </div>
        </div>

        <!-- Card Diantar -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 transition-all duration-300 hover:shadow-xl hover:scale-105 hover:-translate-y-1 hover:border-blue-400 hover:shadow-blue-100 group cursor-pointer">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full group-hover:animate-pulse"></div>
                        <div class="text-lg font-semibold text-blue-700">Diantar</div>
                    </div>
                    <div class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $orders->where('status', 'antar')->count() }}
                    </div>
                </div>
                <div class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs font-medium group-hover:bg-blue-200 group-hover:scale-110 transition-all">
                    {{ $orders->count() > 0 ? round(($orders->where('status', 'antar')->count() / $orders->count()) * 100, 1) : 0 }}%
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-700 group-hover:bg-gradient-to-r from-blue-500 to-blue-400" 
                    style="width: {{ $orders->count() > 0 ? ($orders->where('status', 'antar')->count() / $orders->count()) * 100 : 0 }}%"></div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Tabel Tracking -->
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gradient-to-r from-[#5F9233] to-[#6ba83a] text-white">
                    <tr>
                        <th class="px-4 py-3 text-center font-semibold">NO</th>
                        <th class="px-4 py-3 text-center font-semibold">NAMA PELANGGAN</th>
                        <th class="px-4 py-3 text-center font-semibold">STATUS</th>
                        <th class="px-4 py-3 text-center font-semibold">TANGGAL PEMESANAN</th>
                    </tr>
                </thead>

                <tbody id="tracking-data" class="divide-y divide-gray-100">
                    @forelse ($orders as $index => $order)
                    <tr class="tracking-row hover:bg-gray-50/80 transition-colors duration-200" 
                        data-customer-name="{{ strtolower($order->customer_name) }}"
                        data-status="{{ $order->status }}">
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center text-xs font-medium text-gray-700 bg-gray-100 px-2 py-1 rounded-full min-w-[40px]">
                                {{ $index + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}
                            </span>
                        </td>
                        
                        <td class="px-4 py-3 text-center">
                            <div class="font-medium text-gray-900">{{ $order->customer_name }}</div>
                            <div class="text-xs text-gray-500 mt-1">ID: {{ $order->id }}</div>
                        </td>
                        
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center">
                                <select name="status" 
                                        data-order-id="{{ $order->id }}"
                                        onchange="updateStatus(this)"
                                        class="status-select px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#5F9233] focus:outline-none transition-all duration-200 bg-white shadow-sm">
                                    <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="siap_antar" {{ $order->status == 'siap_antar' ? 'selected' : '' }}>Siap Diantar</option>
                                    <option value="antar" {{ $order->status == 'antar' ? 'selected' : '' }}>Diantar</option>
                                    <option value="sampai_tujuan" {{ $order->status == 'sampai_tujuan' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>
                        </td>

                        <td class="px-4 py-3 text-center">
                            <div class="text-gray-700 font-medium">{{ $order->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }} WIB</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-lg font-medium text-gray-600 mb-1">Tidak ada data tracking</p>
                                <p class="text-sm text-gray-500">Belum ada pesanan laundry yang tercatat</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer dengan Pagination -->
        @if($orders->hasPages())
        <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            {{ $orders->onEachSide(1)->links() }}
        </div>
        @endif
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function updateStatus(selectElement) {
    const orderId = selectElement.getAttribute('data-order-id');
    const newStatus = selectElement.value;
    
    console.log('🔄 Starting updateStatus:', { orderId, newStatus });
    
    // Show loading state
    selectElement.disabled = true;
    selectElement.classList.add('opacity-50', 'cursor-not-allowed');

    // Prepare form data
    const formData = new FormData();
    formData.append('status', newStatus);
    formData.append('_token', '{{ csrf_token() }}');

    const url = `/dashboard/tracking/${orderId}/status`;
    console.log('📤 Making POST request to:', url);

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async response => {
        console.log('📥 Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('✅ Response data:', data);
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Status berhasil diperbarui',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            
            // Update status attribute for filtering
            const row = selectElement.closest('.tracking-row');
            row.setAttribute('data-status', newStatus);
            
            // Refresh page after success
            setTimeout(() => {
                window.location.reload();
            }, 1500);
            
        } else {
            throw new Error(data.message || 'Unknown error from server');
        }
    })
    .catch(error => {
        console.error('❌ Error in updateStatus:', error);
        
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000
        });
    })
    .finally(() => {
        // Re-enable select
        selectElement.disabled = false;
        selectElement.classList.remove('opacity-50', 'cursor-not-allowed');
    });
}

// Search and Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const resetFilter = document.getElementById('resetFilter');
    const filterInfo = document.getElementById('filterInfo');
    const mobileFilterInfo = document.getElementById('mobileFilterInfo');
    const resultCount = document.getElementById('resultCount');
    const mobileResultCount = document.getElementById('mobileResultCount');
    const rows = document.querySelectorAll('.tracking-row');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const statusValue = statusFilter.value;
        
        let visibleCount = 0;
        
        rows.forEach(row => {
            const customerName = row.getAttribute('data-customer-name');
            const status = row.getAttribute('data-status');
            
            const matchesSearch = !searchTerm || customerName.includes(searchTerm);
            const matchesStatus = !statusValue || status === statusValue;
            
            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show/hide filter info
        if (searchTerm || statusValue) {
            filterInfo.classList.remove('hidden');
            mobileFilterInfo.classList.remove('hidden');
            resultCount.textContent = `${visibleCount} hasil ditemukan`;
            mobileResultCount.textContent = `${visibleCount} hasil ditemukan`;
        } else {
            filterInfo.classList.add('hidden');
            mobileFilterInfo.classList.add('hidden');
            // Saat tidak ada filter, count harus total semua rows
            resultCount.textContent = `${rows.length} total pesanan`;
            mobileResultCount.textContent = `${rows.length} total pesanan`;
        }
    }
    
    // Event listeners
    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
    
    resetFilter.addEventListener('click', function() {
        searchInput.value = '';
        statusFilter.value = '';
        filterTable();
        searchInput.focus();
    });
    
    // Initialize
    filterTable();
    console.log('✅ Tracking page loaded successfully');
});
</script>

<style>
    /* Custom styling for status dropdowns */
    .status-select option[value="diproses"] { color: #eab308; } /* kuning */
    .status-select option[value="siap_antar"] { color: #f97316; } /* orange */
    .status-select option[value="antar"] { color: #3b82f6; } /* biru */
    .status-select option[value="sampai_tujuan"] { color: #10b981; } /* hijau */
    .status-select option[value="cancelled"] { color: #ef4444; } /* merah */
    
    /* Smooth transitions */
    .tracking-row {
        transition: all 0.2s ease-in-out;
    }
    
    /* Custom scrollbar */
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
    
    /* Pagination styling */
    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        margin: 0;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .pagination li {
        display: inline-block;
    }
    
    .pagination li a,
    .pagination li span {
        display: inline-block;
        padding: 0.5rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        color: #4b5563;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 0.875rem;
    }
    
    .pagination li.active span {
        background-color: #5F9233;
        border-color: #5F9233;
        color: white;
    }
    
    .pagination li:not(.active) a:hover {
        background-color: #f8fafc;
        border-color: #5F9233;
        color: #5F9233;
    }
    
    .pagination li.disabled span {
        color: #9ca3af;
        background-color: #f3f4f6;
        border-color: #e5e7eb;
        cursor: not-allowed;
    }
</style>
@endsection