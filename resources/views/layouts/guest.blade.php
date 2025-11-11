<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laundry Time') }}</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('image/logo.webp') }}" type="image/webp">

    <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Background Full Screen -->
        <div class="min-h-screen bg-cover bg-center relative" 
            style="background-image: linear-gradient(to right, rgba(169, 201, 125, 0.85), rgba(169, 201, 125, 0) 70%), url('{{ asset('image/bg-laundry.jpg') }}')">
            
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/20"></div>

            <!-- Content Container -->
            <div class="relative min-h-screen flex">
                <!-- Left Side - Welcome Section with Green Overlay -->
                <div class="hidden lg:flex lg:w-1/2 items-center justify-center p-12 relative">
                    
                    <div class="relative max-w-md text-white z-10">
                        <h1 class="text-4xl font-bold mb-6">Selamat Datang di Laundry Time</h1>
                        <p class="text-lg mb-4">Silahkan login untuk mengakses dashboard dan mengelola layanan laundry Anda dengan mudah dan efisien.</p>
                        <div class="mt-8 space-y-4">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Kelola pesanan dengan efisien</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Tracking status laundry real-time</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Laporan lengkap dan detail</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Login Form with semi-transparent overlay -->
                <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 relative rounded-l-3xl"
                style="background-color: rgba(169, 201, 125, 0.9);">

                    <div class="relative w-full sm:max-w-md px-6 py-6 border overflow-hidden sm:rounded-lg z-10" 
>
                        <div class="flex justify-center">
                            <a>
                                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                            </a>
                        </div>
                        <div class="flex justify-center mb-4">
                            <p class="mt-2 text-lg font-semibold text-gray-700">
                                Laundry Time
                            </p>
                        </div>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>