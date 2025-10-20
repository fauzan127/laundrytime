<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
    <td class="px-4 py-2 border">{{ $item->nama_pelanggan }}</td>
    <td class="px-4 py-2 border">{{ $item->no_hp_pelanggan }}</td>
    <td class="px-4 py-2 border">{{ $item->layanan }}</td>
    <td class="px-4 py-2 border">{{ $item->berat_kg }}</td>
    <td class="px-4 py-2 border">{{ $item->status_layanan }}</td>
    <td class="px-4 py-2 border">{{ $item->tanggal_masuk }}</td>
    <td class="px-4 py-2 border text-center">
      <a href="{{ route('detail_keluar.show', $item->id) }}" 
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
document.addEventListener("DOMContentLoaded", loadDataTransaksi);

const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('toggleBtn');
const sidebarTexts = document.querySelectorAll('.sidebar-text');
const sidebarTitle = document.getElementById('sidebarTitle');
const toggleIcon = document.getElementById('toggleIcon');
const sidebarNav = document.getElementById('sidebarNav');
const navItems = document.querySelectorAll('.nav-item');
const tbodyTransaksi = document.getElementById('data-transaksi');
const searchInput = document.getElementById('searchInput');

// --- FUNGSI CUSTOM MODAL (pengganti alert) ---
function showCustomModal(title, message) {
  const modal = document.getElementById('custom-modal');
  document.getElementById('modal-title').textContent = title;
  document.getElementById('modal-message').textContent = message;
  modal.classList.remove('hidden');
  document.getElementById('modal-close-btn').onclick = () => {
    modal.classList.add('hidden');
  };
}

// --- FUNGSI UPDATE STATUS (Simulasi) ---
function updateStatus(id, newStatus) {
  console.log(`Mengupdate ID ${id} menjadi status: ${newStatus}`);
  showCustomModal('Update Status', `Status ID: ${id} telah diubah menjadi: ${newStatus}.`);
}

// --- FUNGSI UTAMA: MUAT DATA + PAGINATION ---
async function loadDataTransaksi() {
  const API_URL_TRANSAKSI = '/api/kainkeluar/list';
  try {
    tbodyTransaksi.innerHTML = `
      <tr>
        <td colspan="8" class="text-center py-4 text-gray-500">
          Memuat data transaksi...
        </td>
      </tr>`;

    const response = await fetch(API_URL_TRANSAKSI);
    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

    const data = await response.json();
    tbodyTransaksi.innerHTML = '';

    if (data.length === 0) {
      tbodyTransaksi.innerHTML = `
        <tr><td colspan="8" class="text-center py-4 text-gray-500">
          Tidak ada data transaksi ditemukan.
        </td></tr>`;
      return;
    }

    // Pagination
    const rowsPerPage = 10;
    let currentPage = 1;
    const totalPages = Math.ceil(data.length / rowsPerPage);

    function renderTablePage(page) {
      tbodyTransaksi.innerHTML = '';
      const start = (page - 1) * rowsPerPage;
      const end = start + rowsPerPage;
      const pageData = data.slice(start, end);

      pageData.forEach((row, index) => {
        const tr = document.createElement('tr');
        const statusOptions = ['Diproses', 'Siap antar', 'Pengantaran', 'Sampai Tujuan'];
        const optionsHtml = statusOptions
          .map(s => `<option value="${s}" ${row.status_layanan === s ? 'selected' : ''}>${s}</option>`)
          .join('');
        tr.innerHTML = `
          <td class="border px-4 py-2">${start + index + 1}</td>
          <td class="border px-4 py-2">${row.nama_pelanggan}</td>
          <td class="border px-4 py-2">${row.no_hp_pelanggan}</td>
          <td class="border px-4 py-2">${row.layanan}</td>
          <td class="border px-4 py-2">${parseFloat(row.berat_kg).toFixed(2)} kg</td>
          <td class="border px-4 py-2">
            <select onchange="updateStatus(${row.id}, this.value)">
              ${optionsHtml}
            </select>
          </td>
          <td class="border px-4 py-2">${row.tanggal_masuk}</td>
          <td class="border px-4 py-2 text-center">
            <a href="/detailkainkeluar/${row.id}" class="text-black">
              <span class="material-icons-outlined">visibility</span>
            </a>
          </td>`;
        tbodyTransaksi.appendChild(tr);
      });

      renderPagination();
    }

    function renderPagination() {
      const container = document.getElementById('pagination-container');
      container.innerHTML = `
        <button ${currentPage === 1 ? 'disabled' : ''} onclick="currentPage--; renderTablePage(currentPage)">Prev</button>
        <span>${currentPage} / ${totalPages}</span>
        <button ${currentPage === totalPages ? 'disabled' : ''} onclick="currentPage++; renderTablePage(currentPage)">Next</button>
      `;
    }

    renderTablePage(currentPage);

  } catch (error) {
    tbodyTransaksi.innerHTML = `
      <tr><td colspan="8" class="text-center text-red-600">Error: ${error.message}</td></tr>`;
  }
} // <-- Jangan lupa ini penutup function

</script>

</body>
</html>