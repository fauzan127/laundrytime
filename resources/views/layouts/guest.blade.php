<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Login') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="fixed inset-0 bg-white/10 backdrop-blur-sm z-0"></div>
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-cover bg-center"
            style="background-image: linear-gradient(to right, rgba(169, 201, 125, 0.85), rgba(169, 201, 125, 0) 70%), url('{{ asset('image/bg-laundry.jpg') }}')">


            <div class="relative w-full sm:max-w-md mt-6 px-6 py-4 shadow-md overflow-hidden sm:rounded-lg" 
                style="background-color: #A9C97D;">
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

    </body>
</html>
