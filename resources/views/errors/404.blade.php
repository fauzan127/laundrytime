<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex flex-col justify-center items-center min-h-screen bg-gradient-to-r from-green-100 to-white text-gray-800">

    <!-- Gambar ilustrasi -->
    <div class="text-center px-6">
        <img src="{{ asset('image/404.webp') }}" alt="404 Not Found" class="w-3/4 mx-auto mb-8">
        
        <!-- Tombol kembali -->
        <a href="{{ url('/') }}"
           class="bg-green-200 hover:bg-green-500 hover:text-white font-semibold py-4 px-8 rounded-lg transition">
           Beranda
        </a>
    </div>

</body>
</html>
