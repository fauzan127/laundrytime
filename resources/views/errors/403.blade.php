<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex flex-col justify-center items-center min-h-screen bg-gradient-to-r from-green-100 to-white text-gray-800">

    <!-- Gambar ilustrasi -->
    <div class="text-center px-6">
        <img src="{{ asset('image/403.png') }}" alt="403 Forbidden" class="w-3/4 mx-auto mb-6">

        <!-- Pesan -->
        {{-- <h1 class="text-3xl font-bold text-green-700 mb-2">403 ERROR FORBIDDEN</h1>
        <p class="text-gray-600 mb-6">
            Hmm... sepertinya kamu belum punya izin untuk membuka halaman ini.<br>
            Yuk kembali ke beranda.
        </p> --}}

        <!-- Tombol kembali -->
        <a href="{{ url('/') }}"
           class="bg-gray-200 hover:bg-green-500 hover:text-white font-semibold py-2 px-6 rounded-lg transition">
           Beranda
        </a>
    </div>

</body>
</html>
