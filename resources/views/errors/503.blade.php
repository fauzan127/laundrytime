<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 Forbidden</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex flex-col justify-center items-center min-h-screen bg-gradient-to-r from-green-100 to-white text-gray-800">

    <!-- Gambar ilustrasi -->
    <div class="text-center px-6">
        <img src="{{ asset('image/503.png') }}" alt="403 Forbidden" class="w-3/4 mx-auto mb-6">

       
        <h1 class="text-3xl font-bold text-green-700">403 ERROR FORBIDDEN</h1>
        <p class="text-gray-600 mb-6">
            Time Laundry sedang sibuk banget nih. Layanan sementara tidak tersedia. Silakan coba beberapa menit lagi
        </p> 

        <!-- Tombol kembali -->
        <a href="{{ url('/') }}"
           class="bg-gray-200 hover:bg-green-503 hover:text-white font-semibold py-2 px-6 rounded-lg transition">
           Muat Ulang
        </a>
    </div>

</body>
</html>
