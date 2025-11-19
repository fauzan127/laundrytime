<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

<aside id="sidebar"
  class="bg-[#5F9233] text-white w-48 transition-all duration-300 flex flex-col justify-between py-6 shadow-none min-h-screen">

  <!-- BAGIAN ATAS -->
  <div class="flex flex-col flex-1 items-center">

    <!-- Tombol toggle di atas foto profil -->
    <div class="flex justify-center mb-4">
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
      <a href="/dashboard/index"
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
  <div id="logoutContainer" class="flex justify-start px-4 mt-6 transition-all duration-300">
    <button id="logoutBtn"
      onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
      class="nav-item flex items-center justify-start w-4/5 hover:bg-red-700 px-4 py-2 text-white space-x-3 rounded-md mb-2 transition-colors duration-200">
      <span class="material-icons-outlined">logout</span>
      <span class="sidebar-text transition-opacity duration-300">Logout</span>
    </button>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
    </form>
  </div>
</aside>

<!-- Script -->
<script>
  const sidebar = document.getElementById('sidebar');
  const toggleBtn = document.getElementById('toggleBtn');
  const sidebarTexts = document.querySelectorAll('.sidebar-text');
  const toggleIcon = document.getElementById('toggleIcon');
  const navItems = document.querySelectorAll('.nav-item');
  const logoutContainer = document.getElementById('logoutContainer');

  toggleBtn.addEventListener('click', () => {
    const isOpen = sidebar.classList.contains('w-48');

    sidebar.classList.toggle('w-20', isOpen);
    sidebar.classList.toggle('w-48', !isOpen);

    sidebarTexts.forEach(el => {
      if (isOpen) {
        el.classList.add('opacity-0');
        setTimeout(() => el.classList.add('hidden'), 200);
      } else {
        el.classList.remove('hidden');
        setTimeout(() => el.classList.remove('opacity-0'), 50);
      }
    });

    toggleIcon.textContent = isOpen ? 'menu' : 'chevron_left';

    navItems.forEach(item => {
      item.classList.toggle('justify-center', isOpen);
      item.classList.toggle('justify-start', !isOpen);
    });

    logoutContainer.classList.toggle('justify-center', isOpen);
    logoutContainer.classList.toggle('justify-start', !isOpen);
  });
</script>
