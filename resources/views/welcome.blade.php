<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem Laundry</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html {
      scroll-behavior: smooth;
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-900">

  <!-- Navbar -->
  <nav class="fixed w-full bg-white shadow-md z-50">
    <div class="max-w-6xl mx-auto py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-cyan-600">LaundryTime</h1>
      <ul class="flex space-x-8">
        <li><a href="#home" class="hover:text-cyan-600">Home</a></li>
        <li><a href="#services" class="hover:text-cyan-600">Services</a></li>
        <li><a href="#pricing" class="hover:text-cyan-600">Pricing</a></li>
      </ul>
    </div>
  </nav>

  <!-- Hero / Home Section -->
  ```html
<section id="home" 
  class="h-screen flex items-center justify-center px-6 bg-cover bg-center bg-no-repeat" 
  style="background-image: linear-gradient(to right, rgba(196, 228, 208, 0.7), rgba(123, 198, 150, 0.3)), url('{{ asset('image/bg-laundry.jpg') }}');">
  <div class="max-w-3xl">
    <h2 class="text-5xl font-bold mb-4 leading-tight">
      Waktumu berharga.<br>
      <span class="text-gray-900">Cucianmu?</span> biar <span class="font-extrabold text-cyan-700">kami</span> yang urus.
    </h2>
    <p class="text-lg md:text-xl text-gray-700 mb-8">
      <span class="font-semibold italic text-gray-900">Time Laundry</span> siap membuat pakaianmu bersih, rapi,
      dan wangi tanpa bikin kamu kehilangan waktu berharga.
    </p>
    <a href="#services" class="inline-block bg-white border-2 border-gray-700 text-gray-900 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
      Pesan Sekarang
    </a>
  </div>
</section>


  <!-- Services Section -->
  <section id="services" class="h-screen flex items-center bg-white px-6">
    <div class="max-w-6xl mx-auto text-center">
      <h3 class="text-4xl font-bold mb-12">Layanan Kami</h3>
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-cyan-50 p-6 rounded-xl shadow">
          <h4 class="text-2xl font-semibold mb-3">Cuci Kering</h4>
          <p>Pakaian Anda dicuci dengan mesin modern, cepat kering, dan rapi.</p>
        </div>
        <div class="bg-cyan-50 p-6 rounded-xl shadow">
          <h4 class="text-2xl font-semibold mb-3">Cuci Setrika</h4>
          <p>Selain dicuci, pakaian juga disetrika agar siap langsung dipakai.</p>
        </div>
        <div class="bg-cyan-50 p-6 rounded-xl shadow">
          <h4 class="text-2xl font-semibold mb-3">Antar Jemput</h4>
          <p>Kami sediakan layanan antar-jemput gratis untuk wilayah tertentu.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Pricing Section -->
  <section id="pricing" class="h-screen flex items-center bg-gray-100 px-6">
    <div class="max-w-6xl mx-auto text-center">
      <h3 class="text-4xl font-bold mb-12">Harga Paket</h3>
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-xl shadow">
          <h4 class="text-2xl font-semibold mb-4">Basic</h4>
          <p class="text-3xl font-bold mb-4">Rp 20.000</p>
          <p>Cuci kering hingga 3kg.</p>
        </div>
        <div class="bg-cyan-600 text-white p-8 rounded-xl shadow transform scale-105">
          <h4 class="text-2xl font-semibold mb-4">Premium</h4>
          <p class="text-3xl font-bold mb-4">Rp 35.000</p>
          <p>Cuci + setrika hingga 5kg.</p>
        </div>
        <div class="bg-white p-8 rounded-xl shadow">
          <h4 class="text-2xl font-semibold mb-4">VIP</h4>
          <p class="text-3xl font-bold mb-4">Rp 50.000</p>
          <p>Cuci + setrika + antar jemput hingga 7kg.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-cyan-600 text-white py-6 text-center">
    <p>&copy; 2025 LaundryKu. Semua Hak Dilindungi.</p>
  </footer>

</body>
</html>
```
