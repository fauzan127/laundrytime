<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard Kain Keluar</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body class="bg-gradient-to-b from-[#CCE1B8] to-white text-gray-800">

  <div class="flex h-screen overflow-hidden">
  
  

    <!-- Sidebar -->
    <aside id="sidebar" class="bg-[#5F9233] text-gray-700 w-20 transition-all duration-300 flex flex-col justify-between py-6">

      <!-- Bagian atas (profil & menu) -->
      <div>
        <!-- Tombol toggle -->
        <div class="flex items-center justify-center px-4 mb-6">
          <button id="toggleBtn" class="w-10 h-10 flex items-center justify-center rounded-md hover:bg-lime-800">
            <span class="material-icons-outlined text-white" id="toggleIcon">menu</span>
          </button>
        </div>

        <!-- Foto Profil -->
        <div class="flex flex-col items-center space-y-4">
          <img src="https://i.pravatar.cc/100" alt="Profil" class="w-16 h-16 rounded-full border-2 border-white object-cover">
          <h1 id="sidebarTitle" class="text-white text-lg font-semibold hidden">Admin</h1>
        </div>

        <!-- Menu Navigasi -->
        <nav id="sidebarNav" class="mt-8 flex flex-col space-y-2 items-center w-full transition-all duration-300">
          <button class="nav-item flex items-center justify-center w-4/5 hover:bg-lime-800 px-4 py-2 text-white space-x-3 rounded-md">
            <span class="material-icons-outlined">home</span>
            <span class="hidden sidebar-text">Home</span>
          </button>
          <button class="nav-item flex items-center justify-center w-4/5 hover:bg-lime-800 px-4 py-2 text-white space-x-3 rounded-md">
            <span class="material-icons-outlined">content_paste</span>
            <span class="hidden sidebar-text">Order</span>
           </button>
          <button class="nav-item flex items-center justify-center w-4/5 hover:bg-lime-800 px-4 py-2 text-white space-x-3 rounded-md">
            <span class="material-icons-outlined">motorcycle</span>
            <span class="hidden sidebar-text">Tracking</span>
          </button>
           <button class="nav-item flex items-center justify-center w-4/5 hover:bg-lime-800 px-4 py-2 text-white space-x-3 rounded-md">
            <span class="material-icons-outlined">analytics</span>
            <span class="hidden sidebar-text">Report</span>
          </button>
          <button class="nav-item flex items-center justify-center w-4/5 hover:bg-lime-800 px-4 py-2 text-white space-x-3 rounded-md">
            <span class="material-icons-outlined">people</span>
            <span class="hidden sidebar-text">Profile</span>
          </button>
        </nav>
      </div>

      <!-- Logout -->
      <div class="mt-auto px-4">
        <button class="nav-item flex items-center justify-center w-4/5 hover:bg-lime-800 px-4 py-2 text-white space-x-3 rounded-md">
          <span class="material-icons-outlined">logout</span>
          <span class="hidden sidebar-text">Logout</span>
        </button>
      </div>
    </aside>

    <!-- Konten Utama -->
    <main class="flex-1 bg-gradient-to-b from-[#CCE1B8] to-white p-6 overflow-x-auto">

      <!-- Bar atas -->
      <div class="flex justify-end md:justify-between items-center mb-6 flex-wrap gap-3 p-4 rounded-md sticky top-0 z-10">
        <h2 class="text-xl font-semibold text-green-800 hidden md:block">Dashboard Kain Keluar</h2>

        <!-- Pencarian -->
        <div class="flex w-full sm:w-1/2 md:w-1/3">
          <input id="searchInput" type="text" placeholder="Cari" class="flex-1 px-4 py-2 border border-[#5F9233] rounded-l-lg focus:outline-none focus:ring-2 focus:ring-[#5F9233]">
          <button class="bg-[#5F9233] text-white px-4 rounded-r-lg flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </button>
        </div>
      </div>
<!-- Tabel -->
<div class="overflow-x-auto bg-white p-4 rounded-lg shadow-md">
  <table class="min-w-full border border-gray-300 text-sm">
    <thead class="bg-[#9EC37D] text-gray-700">
      <tr>
        <th class="px-4 py-2 border">No</th>
        <th class="px-4 py-2 border">Nama</th>
        <th class="px-4 py-2 border">No. Hp</th>
        <th class="px-4 py-2 border">Layanan</th>
        <th class="px-4 py-2 border">Berat</th>
        <th class="px-4 py-2 border">Status</th>
        <th class="px-4 py-2 border">Tanggal Masuk</th>
        <th class="px-4 py-2 border">Aksi</th>
      </tr>
    </thead>

    <tbody id="data-transaksi" class="bg-gray-100 text-center">
  @foreach($data as $item)
  <tr>
    <td class="px-4 py-2 border">{{ $item->id }}</td>
    <td class="px-4 py-2 border">{{ $item->customer_name }}</td>
    <td class="px-4 py-2 border">{{ $item->customer_phone }}</td>
    <td class="px-4 py-2 border">{{ $item->delivery_type }}</td>
    <td class="px-4 py-2 border">{{ $item->weight ? number_format($item->weight, 2) . ' kg' : '-'}}</td>
    <td class="px-4 py-2 border text-center">
      <select class="status-dropdown" data-order-id="{{ $item->id }}">
            <option value="Pending" {{ $item->status == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Diproses" {{ $item->status == 'Processing' ? 'selected' : '' }}>Diproses</option>
            <option value="Sampai Tujuan" {{ $item->status == 'Completed' ? 'selected' : '' }}>Selesai</option>
            <option value="Antar" {{ $item->status == 'Cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
    </td>
    <td class="px-4 py-2 border">{{ $item->created_at }}</td>
    <td class="px-4 py-2 border text-center">
  <a href="{{ route('detailkainkeluar', ['id' => $item->id]) }}" 
     class="bg-[#35623E] hover:bg-[#2b4d31] text-white p-2 rounded-md inline-flex items-center justify-center transition-all"
     title="Lihat Detail">
    <span class="material-icons-outlined text-base">visibility</span>
  </a>
</td>
  </tr>
  @endforeach
</tbody>
  </table>
</div>

<!-- Pagination -->
<div id="pagination-container" 
     class="flex justify-center items-center gap-3 mt-6 text-[#35623E] font-semibold">
</div>



    </div>
    <div id="pagination-container" class="flex justify-center mb-8"></div>

    <div id="info-pesanan-detail" class="text-gray-800 text-base leading-relaxed space-y-1"></div>
    </main>
</body>

<script>
const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('toggleBtn');
const sidebarTexts = document.querySelectorAll('.sidebar-text');
const sidebarTitle = document.getElementById('sidebarTitle');
const toggleIcon = document.getElementById('toggleIcon');
const tbodyTransaksi = document.getElementById('data-transaksi');
const searchInput = document.getElementById('searchInput');

// Variabel global
let currentPage = 1;
let rowsPerPage = 10;
let allData = [];

// 🔹 UPDATE STATUS KE DATABASE
async function updateStatus(orderId, newStatus) {
    console.log('🔄 Update status:', orderId, newStatus);
    try {
        if (!confirm(`Ubah status pesanan ID ${orderId} menjadi "${newStatus}"?`)) {
            loadDataTransaksi();
            return;
        }

        const response = await fetch('/api/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                order_id: orderId,
                status: newStatus
            })
        });

        const result = await response.json();
        console.log('📡 Update response:', result);

        if (result.success) {
            alert(`✅ Status pesanan ID ${orderId} berhasil diubah menjadi ${newStatus}`);
            loadDataTransaksi();
        } else {
            alert(`❌ Gagal update status: ${result.message || 'Unknown error'}`);
            loadDataTransaksi();
        }
    } catch (error) {
        console.error('❌ Error update status:', error);
        alert('Terjadi kesalahan saat update status.');
        loadDataTransaksi();
    }
}

// 🔹 INISIALISASI DROPDOWN STATUS
function initializeDropdowns() {
    document.querySelectorAll('.status-dropdown').forEach(dropdown => {
        dropdown.addEventListener('change', function() {
            const orderId = this.getAttribute('data-order-id');
            const newStatus = this.value;
            updateStatus(orderId, newStatus);
        });
    });
}

// 🔹 CARI DATA
function handleSearch() {
    const searchTerm = searchInput.value.toLowerCase();
    const filteredData = allData.filter(row => 
        (row.customer_name || '').toLowerCase().includes(searchTerm) ||
        (row.customer_phone || '').toLowerCase().includes(searchTerm) ||
        (row.delivery_type || '').toLowerCase().includes(searchTerm) ||
        (row.status || '').toLowerCase().includes(searchTerm)
    );

    currentPage = 1;
    renderTablePage(currentPage, filteredData);
}

// 🔹 TAMPILKAN TABEL
function renderTablePage(page, data = allData) {
    tbodyTransaksi.innerHTML = '';

    if (data.length === 0) {
        tbodyTransaksi.innerHTML = `
        <tr><td colspan="8" class="text-center py-4 text-gray-500">Tidak ada data ditemukan.</td></tr>`;
        renderPagination(data);
        return;
    }

    const totalPages = Math.ceil(data.length / rowsPerPage);
    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const pageData = data.slice(start, end);

    pageData.forEach((row, index) => {
        const options = ['Pending', 'Diproses', 'Antar', 'Sampai Tujuan']
            .map(status => `<option value="${status}" ${row.status === status ? 'selected' : ''}>${status}</option>`)
            .join('');

        tbodyTransaksi.innerHTML += `
        <tr>
            <td class="border px-4 py-2">${start + index + 1}</td>
            <td class="border px-4 py-2">${row.customer_name || '-'}</td>
            <td class="border px-4 py-2">${row.customer_phone || '-'}</td>
            <td class="border px-4 py-2">${row.delivery_type || '-'}</td>
            <td class="border px-4 py-2">${row.weight ? parseFloat(row.weight).toFixed(2) + ' kg' : '-'}</td>
            <td class="border px-4 py-2">
                <select class="status-dropdown border rounded p-1 text-sm" data-order-id="${row.id}">
                    ${options}
                </select>
            </td>
            <td class="border px-4 py-2">${formatDate(row.created_at)}</td>
            <td class="border px-4 py-2 text-center">
                <a href="/detailkainkeluar/${row.id}" 
                   class="text-[#35623E] hover:text-lime-700 transition-colors">
                   <span class="material-icons-outlined text-lg">visibility</span>
                </a>
            </td>
        </tr>`;
    });

    initializeDropdowns();
    renderPagination(data);
}

// 🔹 FORMAT TANGGAL
function formatDate(dateStr) {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    return date.toLocaleString('id-ID', { 
        day: '2-digit', month: '2-digit', year: 'numeric', 
        hour: '2-digit', minute: '2-digit' 
    });
}

// 🔹 PAGINATION
function renderPagination(data = allData) {
    const container = document.getElementById('pagination-container');
    const totalPages = Math.ceil(data.length / rowsPerPage);
    if (totalPages <= 1) return container.innerHTML = '';

    container.innerHTML = `
        <div class="flex items-center justify-center space-x-4 mt-4">
            <button onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}
                class="px-3 py-1 bg-gray-200 rounded ${currentPage === 1 ? 'opacity-50' : 'hover:bg-gray-300'}">Prev</button>
            <span>${currentPage} / ${totalPages}</span>
            <button onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}
                class="px-3 py-1 bg-gray-200 rounded ${currentPage === totalPages ? 'opacity-50' : 'hover:bg-gray-300'}">Next</button>
        </div>
    `;
}
function changePage(page) {
    if (page < 1 || page > Math.ceil(allData.length / rowsPerPage)) return;
    currentPage = page;
    renderTablePage(currentPage, allData);
}

// 🔹 LOAD DATA DARI DATABASE
async function loadDataTransaksi() {
    try {
        tbodyTransaksi.innerHTML = `
        <tr><td colspan="8" class="text-center py-4 text-gray-500">
        <div class="flex justify-center items-center">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-green-700"></div>
            <span class="ml-2">Memuat data...</span>
        </div></td></tr>`;

        const response = await fetch('/api/orders/list');
        if (!response.ok) throw new Error('Gagal memuat data');

        const data = await response.json();
        console.log('📦 Data loaded:', data);

        allData = Array.isArray(data) ? data.reverse() : [];
        renderTablePage(currentPage, allData);
    } catch (error) {
        console.error('Error loading data:', error);
        tbodyTransaksi.innerHTML = `
        <tr><td colspan="8" class="text-center text-red-600 py-4">
        Gagal memuat data: ${error.message}</td></tr>`;
    }
}

// 🔹 EVENT SEARCH
if (searchInput) {
    searchInput.addEventListener('input', handleSearch);
    searchInput.addEventListener('keypress', e => {
        if (e.key === 'Enter') handleSearch();
    });
}

// 🔹 SAAT HALAMAN DILOAD
document.addEventListener('DOMContentLoaded', () => {
    console.log('🚀 DOM Ready, loading data...');
    initializeDropdowns();
    loadDataTransaksi();
});
</script>


</body>
</html>