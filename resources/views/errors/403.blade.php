<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex flex-col justify-center items-center min-h-screen bg-gradient-to-r from-red-100 to-white text-gray-800">

    <!-- Gambar ilustrasi -->
    <div class="text-center px-6">
        <img src="{{ asset('image/403.webp') }}" alt="403 Forbidden" class="w-3/4 mx-auto mb-8">
        
        <!-- Tombol kembali -->
        <a href="{{ url('/') }}"
           class="bg-red-300 hover:bg-red-600 hover:text-white font-semibold py-4 px-8 rounded-lg transition">
           Beranda
        </a>
    </div>

</body>
</html>
