<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laundry Time') }}</title>

    <!-- Preconnect untuk percepat koneksi ke font/icon -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Material Icons (pakai preload agar cepat tampil dan tidak flicker) -->
    <link rel="preload" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" as="style" onload="this.rel='stylesheet'">
    <noscript>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    </noscript>

    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Style untuk sembunyikan konten sebelum semua siap -->
    <style>
        body {
            visibility: hidden;
        }
        body.loaded {
            visibility: visible;
            transition: visibility 0s;
        }
    </style>

    <!-- Script: tampilkan konten setelah semua CSS & font siap -->
    <script>
        window.addEventListener('load', function () {
            document.body.classList.add('loaded');
        });
    </script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>
