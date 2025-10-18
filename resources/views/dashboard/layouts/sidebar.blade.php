<div class="flex flex-col md:flex-row-reverse min-h-screen">
    <nav style="background-color: #73b531;" class=" md:w-64 border-l border-gray-200 md:top-0 min-h-screen">
      <ul class="mt-4 flex-gro pl-6">
        <!-- Sidebar content -->
        <h6 class=" block p-2 d-flex justify-content-between align-item-center mt-4 mb-1 font-bold">
          <span>Menu</span>
        </h6>
        <a
          class="block p-2 text-gray-700 nav-link {{ Request::is('dashboard') ? 'active' : '' }}"
          href="/dashboard">
          <i class="bi bi-house-door-fill"></i> Home
        </a>
        {{-- Menu khusus ADMIN --}}
        @if(Auth::user() && Auth::user()->role === 'admin')
        <a
          class="block p-2 text-gray-700 nav-link {{ Request::is('order') ? 'active' : '' }}"
          href="{{ route('order.index') }}">
          <i class="bi bi-building-add"></i> Order
        </a>
        <a
          class="block p-2 text-gray-700 nav-link {{ Request::is('dashboard/output') ? 'active' : '' }}"
          href="/dashboard/dokumen">
          <i class="bi bi-credit-card"></i></i> Output
        </a>
        <a
          class="block p-2 text-gray-700 nav-link {{ Request::is('dashboard/report') ? 'active' : '' }}"
          href="/dashboard/dokumen">
          <i class="bi bi-bar-chart-line"></i> Report
        </a>
        <a
          class="block p-2 text-gray-700 nav-link {{ Request::is('dashboard/profile') ? 'active' : '' }}"
          href="/dashboard/dokumen">
          <i class="bi bi-person-circle"></i> Profile
        </a>
        @endif

        {{-- Menu khusus USER --}}
        @if(Auth::user() && Auth::user()->role === 'user')
        <a
          class="block p-2 text-gray-700 nav-link {{ Request::is('order') ? 'active' : '' }}"
          href="{{ route('order.index') }}">
          <i class="bi bi-building-add"></i> Order
        </a>
        <a
          class="block p-2 text-gray-700 nav-link {{ Request::is('dashboard/payment') ? 'active' : '' }}"
          href="/dashboard/dokumen">
          <i class="bi bi-file-earmark-post"></i> Payment
        </a>
        <a
          class="block p-2 text-gray-700 nav-link {{ Request::is('dashboard/profile') ? 'active' : '' }}"
          href="/dashboard/dokumen">
          <i class="bi bi-person-circle"></i> Profile
        </a>
        @endif
      </ul>
    </nav>
</div>
  
<style>
    .nav-link {
      color: rgb(255, 255, 255); /* Warna teks default */
      text-decoration: none; /* Hilangkan garis bawah */
    }
    .nav-link.active {
      color: blue; /* Warna teks untuk halaman aktif */
      font-weight: bold; /* Tambahkan efek jika diperlukan */
    }
</style>
