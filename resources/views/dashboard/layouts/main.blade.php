<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Dashboard')</title>

  <!-- Favicon -->
  <link rel="icon" href="{{ asset('image/logo.webp') }}" type="image/webp">

  <!-- Fonts & Tailwind -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

  <!-- JS -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  @vite(['resources/css/app.css','resources/js/app.js'])

  <!-- Hilangkan flicker saat load -->
  <style>
    body { visibility: hidden; }
    body.loaded { visibility: visible; transition: visibility 0s; }
  </style>
  <script>
    window.addEventListener('load', () => document.body.classList.add('loaded'));
  </script>
</head>

<body class="bg-gray-100" x-data="{ isMobileMenuOpen: false }">

  <div class="flex min-h-screen">

    {{-- Sidebar --}}
    @include('dashboard.layouts.sidebar')

    {{-- Konten utama --}}
    <main class="flex-1 p-4 md:p-6">
      @yield('container')
    </main>

  </div>

</body>
</html>
