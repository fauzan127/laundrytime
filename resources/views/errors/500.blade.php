<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 Forbidden</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex flex-col justify-center items-center min-h-screen bg-gradient-to-r from-green-100 to-white text-gray-800">

    <!-- Gambar ilustrasi -->
    <div class="text-center px-6">
        <img src="{{ asset('image/500.png') }}" alt="403 Forbidden" class="w-3/4 mx-auto mb-6">

       
        <h1 class="text-3xl font-bold text-green-700">403 ERROR FORBIDDEN</h1>
        <p class="text-gray-600 mb-6">
            Waduh, ada masalah di sistem Time Laundry. 
            Tim kami sedang memperbaikinya. Coba lagi beberapa saat, ya.
        </p> 

        <!-- Tombol kembali -->
        <a href="{{ url('/') }}"
           class="bg-gray-200 hover:bg-green-500 hover:text-white font-semibold py-2 px-6 rounded-lg transition">
           Muat Ulang Halaman
        </a>
    </div>

</body>
</html>
