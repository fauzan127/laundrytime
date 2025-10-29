@extends('dashboard.layouts.main')

@section('container')
<body class="bg-gradient-to-b from-[#CCE1B8] to-white text-gray-800">

  <main class="flex-1 bg-gradient-to-b from-[#CCE1B8] to-white p-6 overflow-x-auto">
    
    <!-- Header -->
    <div class="flex justify-end md:justify-between items-center mb-6 flex-wrap gap-3 p-4 rounded-md sticky top-0 z-10">
      <h2 class="text-2xl font-bold text-[#5F9233]">Tracking Laundry</h2>

      <!-- Pencarian -->
      <div class="flex w-full sm:w-1/2 md:w-1/3">
        <input id="searchInput" type="text" placeholder="Cari Pelanggan..." 
               class="flex-1 px-4 py-2 border border-[#5F9233] rounded-l-lg focus:outline-none focus:ring-2 focus:ring-[#5F9233]">
        <button class="bg-[#5F9233] text-white px-4 rounded-r-lg flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
               viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Tabel Tracking -->
    <div class="overflow-x-auto bg-white p-4 rounded-lg shadow-md">
      <table class="min-w-full border border-gray-300 text-sm">
        <thead class="bg-[#9EC37D] text-gray-700">
          <tr>
            <th class="px-4 py-2 border">No</th>
            <th class="px-4 py-2 border">Nama Pelanggan</th>
            <th class="px-4 py-2 border">Status</th>
            <th class="px-4 py-2 border">Tanggal Masuk</th>
            <th class="px-4 py-2 border">Aksi</th>
          </tr>
        </thead>

        <tbody id="tracking-data" class="bg-gray-100 text-center">
          @foreach ($orders as $index => $order)
          <tr>
            <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
            <td class="border px-4 py-2">{{ $order->customer_name }}</td>
            <td class="border px-4 py-2">
        <form 
    action="{{ route('tracking.updateStatus', $order->id) }}" 
    method="POST" 
    onsubmit="event.preventDefault(); updateStatus(this);"
>
    @csrf
    @method('PUT')
    <select 
        name="status" 
        onchange="this.form.requestSubmit()" 
        class="px-3 pr-6 py-1 border rounded-md text-sm focus:ring-2 focus:ring-[#5F9233] focus:outline-none"
    >
        <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
        <option value="siap_antar" {{ $order->status == 'siap_antar' ? 'selected' : '' }}>Siap Antar</option>
        <option value="antar" {{ $order->status == 'antar' ? 'selected' : '' }}>Sedang Diantar</option>
        <option value="sampai_tujuan" {{ $order->status == 'sampai_tujuan' ? 'selected' : '' }}>Sampai Tujuan</option>
        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
    </select>
</form>


        </form>
        </td>

            <td class="border px-4 py-2">{{ $order->created_at->format('d M Y') }}</td>
            <td class="border px-4 py-2">
              <a href="{{ route('tracking.show', $order->customer_name) }}"
                 class="text-[#35623E] hover:text-lime-700 transition-colors flex items-center justify-center gap-1">
                <span class="material-icons-outlined text-lg">visibility</span>
                Lihat
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

  </main>

  <!-- Script Optional: Pencarian -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const searchInput = document.getElementById('searchInput');
      const rows = document.querySelectorAll('#tracking-data tr');

      searchInput.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        rows.forEach(row => {
          const name = row.children[1]?.textContent.toLowerCase() || '';
          row.style.display = name.includes(term) ? '' : 'none';
        });
      });
    });

    function updateStatus(form) {
    const data = new FormData(form);

    fetch(form.action, {
        method: 'POST', // walau pakai @method('PUT'), tetap POST di fetch
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: data
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // ✅ sukses: reload halaman agar status terbaru tampil
            location.reload();
        } else {
            alert('Gagal memperbarui status: ' + (data.message || 'Terjadi kesalahan'));
            console.log(data.errors);
        }
    })
    .catch(err => console.error('Error:', err));
}
  </script>

</body>
@endsection
