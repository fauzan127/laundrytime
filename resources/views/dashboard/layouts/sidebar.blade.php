 <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

<aside id="sidebar"
  class="bg-[#5F9233] text-white w-48 transition-all duration-300 flex flex-col justify-between py-6 shadow-none min-h-screen">

  <!-- BAGIAN ATAS -->
  <div class="flex flex-col flex-1 items-center">

    <!-- Tombol toggle di atas foto profil -->
  <div class="flex justify-center mb-4" style="display:none;">
      <button id="toggleBtn"
        class="w-10 h-10 flex items-center justify-center rounded-md hover:bg-lime-800 transition-colors duration-300">
        <span class="material-icons-outlined text-white" id="toggleIcon">chevron_left</span>
      </button>
    </div>

    <!-- Foto profil -->
    <div class="flex flex-col items-center space-y-3">
      <img src="https://i.pravatar.cc/100" alt="Profil"
        class="w-16 h-16 rounded-full border-2 border-white object-cover">
    </div>

    <!-- Menu navigasi -->
    <nav id="sidebarNav"
      class="mt-8 flex flex-col space-y-2 items-center w-full transition-all duration-300 flex-1">
      <a href="{{ route('dashboard.index') }}"
        class="nav-item flex items-center justify-start w-4/5 hover:bg-lime-800 px-4 py-2 text-white space-x-3 rounded-md {{ Request::is('dashboard') ? 'bg-lime-800' : '' }}">
        <span class="material-icons-outlined">home</span>
        <span class="sidebar-text transition-opacity duration-300">Home</span>
      </a>

      @if(Auth::user() && Auth::user()->role === 'admin')
      <a href="{{ route('order.index') }}"
        class="nav-item flex items-center justify-start w-4/5 hover:bg-lime-800 px-4 py-2 text-white space-x-3 rounded-md {{ Request::is('order*') ? 'bg-lime-800' : '' }}">
        <span class="material-icons-outlined">content_paste</span>
        <span class="sidebar-text transition-opacity duration-300">Order</span>
      </a>
      <a href="{{ route('admin.payment') }}"
        class="nav-item flex items-center justify-start w-4/5 hover:bg-lime-800 px-4 py-2 text-white space-x-3 rounded-md {{ Request::is('admin/payment') ? 'bg-lime-800' : '' }}">
        <span class="material-icons-outlined">receipt_long</span>
        <span class="sidebar-text transition-opacity duration-300">Admin Payment</span>
      </a>
      <a href="/dashboard/tracking"
        class="nav-item flex items-center justify-start w-4/5 hover:bg-lime-800 px-4 py-2 text-white space-x-3 rounded-md {{ Request::is('dashboard/tracking') ? 'bg-lime-800' : '' }}">
        <span class="material-icons-outlined">motorcycle</span>
        <span class="sidebar-text transition-opacity duration-300">Tracking</span>
      </a>
      <a href="/dashboard/report"
        class="nav-item flex items-center justify-start w-4/5 hover:bg-lime-800 px-4 py-2 text-white space-x-3 rounded-md {{ Request::is('dashboard/report') ? 'bg-lime-800' : '' }}">
        <span class="material-icons-outlined">analytics</span>
        <span class="sidebar-text transition-opacity duration-300">Report</span>
      </a>
      @endif

      @if(Auth::user() && Auth::user()->role === 'user')
      <a href="{{ route('order.index') }}"
        class="nav-item flex items-center justify-start w-4/5 hover:bg-lime-800 px-4 py-2 text-white space-x-3 rounded-md {{ Request::is('order*') ? 'bg-lime-800' : '' }}">
        <span class="material-icons-outlined">content_paste</span>
        <span class="sidebar-text transition-opacity duration-300">Order</span>
      </a>
      <a href="{{ route('payment.index') }}"
        class="nav-item flex items-center justify-start w-4/5 hover:bg-lime-800 px-4 py-2 text-white space-x-3 rounded-md {{ Request::is('payment') ? 'bg-lime-800' : '' }}">
        <span class="material-icons-outlined">credit_card</span>
        <span class="sidebar-text transition-opacity duration-300">Payment</span>
      </a>
      @endif

      <a href="{{ route('profile.index') }}"
        class="nav-item flex items-center justify-start w-4/5 hover:bg-lime-800 px-4 py-2 text-white space-x-3 rounded-md {{ Request::is('dashboard/profile') ? 'bg-lime-800' : '' }}">
        <span class="material-icons-outlined">people</span>
        <span class="sidebar-text transition-opacity duration-300">Profile</span>
      </a>
    </nav>
  </div>

  <!-- Logout -->
  <div class="flex justify-center mt-6 transition-all duration-300 max-w-[75%] mx-auto">
    <button @click.prevent="$dispatch('open-modal', 'logout-confirmation')"
      class="nav-item flex items-center justify-start w-full hover:bg-red-700 px-4 py-2 text-white space-x-3 rounded-md">
      <span class="material-icons-outlined">logout</span>
      <span class="sidebar-text transition-opacity duration-300">Logout</span>
    </button>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
    </form>

    <x-modal name="logout-confirmation" :show="false" @close-modal.window="$dispatch('close-modal', 'logout-confirmation')">
      <div class="p-6 text-center">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Konfirmasi Logout</h2>
        <p class="text-gray-700 dark:text-gray-300 mb-6">Apakah anda yakin ingin logout?</p>
        <div class="flex justify-center space-x-4">
          <button @click="$dispatch('close-modal', 'logout-confirmation')" type="button"
            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600">
            Batal
          </button>
          <button @click.prevent="document.getElementById('logout-form').submit()"
            class="px-4 py-2 bg-red-600 rounded text-white hover:bg-red-700">
            Logout
          </button>
        </div>
      </div>
    </x-modal>
  </div>
</aside>

  <!-- Script -->
  <script>
    const sidebar = document.getElementById('sidebar');
    const sidebarTexts = document.querySelectorAll('.sidebar-text');
    const navItems = document.querySelectorAll('.nav-item');
    const logoutContainer = document.querySelector('div.flex.justify-center.mt-6');

    function expandSidebar() {
      sidebar.classList.remove('w-20');
      sidebar.classList.add('w-48');

      sidebarTexts.forEach(el => {
        el.classList.remove('hidden');
        setTimeout(() => el.classList.remove('opacity-0'), 50);
      });

      navItems.forEach(item => {
        item.classList.remove('justify-center');
        item.classList.add('justify-start');
      });

      logoutContainer.classList.remove('justify-center');
      logoutContainer.classList.add('justify-start');
    }

    function collapseSidebar() {
      sidebar.classList.remove('w-48');
      sidebar.classList.add('w-20');

      sidebarTexts.forEach(el => {
        el.classList.add('opacity-0');
        setTimeout(() => el.classList.add('hidden'), 200);
      });

      navItems.forEach(item => {
        item.classList.add('justify-center');
        item.classList.remove('justify-start');
      });

      logoutContainer.classList.add('justify-center');
      logoutContainer.classList.remove('justify-start');
    }

    sidebar.addEventListener('mouseenter', expandSidebar);
    sidebar.addEventListener('mouseleave', collapseSidebar);

    // Initialize collapsed state
    collapseSidebar();
  </script>
