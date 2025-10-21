<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Time Laundry</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>

<body class="bg-white text-gray-800">


  <!-- Navbar -->
  <nav class="bg-[#5F9233] text-white shadow-md fixed w-full top-0 left-0 z-50">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <a href="#home" class="text-xl font-bold tracking-wide">Time Laundry</a>
      <ul class="hidden md:flex gap-8 font-medium">
        <li><a href="#home" class="hover:text-green-200 transition">Home</a></li>
        <li><a href="#pricing" class="hover:text-green-200 transition">Pricing</a></li>
        <li><a href="#services" class="hover:text-green-200 transition">Services</a></li>
      </ul>
      <div class="hidden md:flex gap-4">
        <a href="#pesanansaya" class="bg-white text-green-700 px-4 py-2 rounded-lg font-semibold hover:bg-green-100 transition">Pesanan Saya</a>
        <a href="#register" class="bg-white text-green-700 px-4 py-2 rounded-lg font-semibold hover:bg-green-100 transition">Register</a>
      </div>
      <button id="menu-btn" class="md:hidden flex flex-col gap-1 focus:outline-none">
        <span class="w-6 h-0.5 bg-white"></span>
        <span class="w-6 h-0.5 bg-white"></span>
        <span class="w-6 h-0.5 bg-white"></span>
      </button>
    </div>

    <div id="mobile-menu" class="hidden md:hidden bg-[#9EC37D] text-white px-6 py-4 space-y-3">
      <a href="#home" class="block hover:text-green-200">Home</a>
      <a href="#pricing" class="block hover:text-green-200">Pricing</a>
      <a href="#services" class="block hover:text-green-200">Services</a>
      <hr class="border-white/20">
      <a href="#pesanansaya" class="block bg-[#CCE1B8] text-green-700 px-4 py-2 rounded-lg font-semibold">Pesanan Saya</a>
      <a href="#register" class="block bg-[#CCE1B8] text-green-700 px-4 py-2 rounded-lg font-semibold">Register</a>
    </div>
  </nav>

  <script>
    document.getElementById('menu-btn').addEventListener('click', function() {
      document.getElementById('mobile-menu').classList.toggle('hidden');
    });
  </script>

  <!-- Hero Section -->
  <section id="home" 
    class="relative flex flex-col md:flex-row items-center justify-center md:justify-between 
    px-6 md:px-16 pt-56 md:pt-64 pb-48 md:pb-56 mt-[100px] text-white bg-cover bg-center bg-no-repeat"
    style="background-image: url('{{ asset('images/landingpage.png') }}'); background-position: center top;">
    <div class="absolute inset-0 bg-gradient-to-b from-[#CCE1B8]/0 via-[#CCE0C9]/43 to-[#CBDFE0]/100"></div>

    <div class="relative z-10 max-w-xl text-center md:text-left">
      <h1 class="text-3xl md:text-5xl font-bold leading-tight text-black">
        Waktumu berharga.<br>
        Cucianmu? <span class="font-normal">biar</span> kami <span class="font-normal">yang urus.</span>
      </h1>
      <p class="mt-6 text-gray-700 text-lg">
        <b>Time Laundry</b> siap membuat pakaianmu bersih, rapi, dan wangi tanpa bikin kamu kehilangan waktu berharga.
      </p>
      <a href="#services"
        class="mt-8 inline-block bg-[#CCE1B8] hover:bg-green-700 text-white px-8 py-4 rounded-lg shadow-lg transition">
        Pesan Sekarang
      </a>
    </div>
  </section>

  <!-- Kenapa Time Laundry -->
  <section id="kenapa" class="py-20 flex flex-col md:flex-row items-center justify-center px-6 md:px-16 bg-gradient-to-b from-[#CBDFE0] to-[#9EC37D]">
    <div class="flex-shrink-0">
      <img src="{{ asset('images/baju.png') }}" alt="Baju Time Laundry"
           class="w-64 md:w-80 rounded-2xl shadow-lg hover:scale-105 transition-transform duration-300">
    </div>
    <div class="mt-8 md:mt-0 md:ml-12 max-w-xl text-center md:text-left">
      <h2 class="text-3xl font-bold mb-4 text-gray-800">
        Kenapa <span class="text-green-700">Time Laundry</span>?
      </h2>
      <p class="text-gray-600 leading-relaxed">
        Kami percaya waktu adalah aset paling berharga. Karena itu, <b>Time Laundry</b> hadir untuk membantu kamu
        menghemat waktu. Dengan peralatan modern, deterjen ramah lingkungan, dan tenaga profesional,
        kami pastikan pakaianmu selalu dalam kondisi terbaik — bersih, rapi, dan wangi.
      </p>
      <a href="#services"
         class="inline-block mt-6 bg-[#5F9233] text-white px-6 py-3 rounded-lg shadow-md hover:bg-green-700 transition">
         Lihat Layanan Kami
      </a>
    </div>
  </section>

  <!-- Pricing Section -->
  <section id="pricing" class="py-20 bg-gradient-to-b from-[#9EC37D] via-[#B9D993]/50 to-[#E0F4CB]">
    <div class="max-w-6xl mx-auto px-6">
      <h2 class="text-3xl font-bold text-center mb-12 text-green-700">Harga & Paket</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
        <!-- Paket Reguler -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
          <div class="bg-[#5F9233] text-white font-semibold px-6 py-3 text-lg">Reguler</div>
          <div class="p-6 space-y-3">
            <label class="flex items-center gap-3">
              <input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" />
              <span>Cuci Setrika – 1 hari <strong>(Rp6.000/kg)</strong></span>
            </label>
            <label class="flex items-center gap-3">
              <input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" />
              <span>Cuci Lipat – 1 hari <strong>(Rp4.000/kg)</strong></span>
            </label>
            <label class="flex items-center gap-3">
              <input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" />
              <span>Cuci Lipat – 2 hari <strong>(Rp3.500/kg)</strong></span>
            </label>
            <label class="flex items-center gap-3">
              <input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" />
              <span>Setrika – 1 hari <strong>(Rp4.000/kg)</strong></span>
            </label>
            <label class="flex items-center gap-3">
              <input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" />
              <span>Setrika – 2 hari <strong>(Rp3.500/kg)</strong></span>
            </label>
          </div>
        </div>

        <!-- Paket Express -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
          <div class="bg-[#5F9233] text-white font-semibold px-6 py-3 text-lg">Express</div>
          <div class="p-6 space-y-3">
            <label class="flex items-center gap-3">
              <input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" />
              <span>Cuci Setrika – 4 jam <strong>(Rp11.000/kg)</strong></span>
            </label>
            <label class="flex items-center gap-3">
              <input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" />
              <span>Cuci Setrika – 6 jam <strong>(Rp8.000/kg)</strong></span>
            </label>
            <label class="flex items-center gap-3">
              <input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" />
              <span>Cuci Lipat – 2 jam <strong>(Rp8.000/kg)</strong></span>
            </label>
            <label class="flex items-center gap-3">
              <input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" />
              <span>Setrika – 2 jam <strong>(Rp8.000/kg)</strong></span>
            </label>
          </div>
        </div>
      </div>

      <!-- Satuan Per Item -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
        <div class="bg-[#5F9233] text-white font-semibold px-6 py-3 text-lg">Satuan Per Item</div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-3"> <label class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" /><span>Bedcover 2kg+ – <strong>Rp25.000</strong></span></label> <label class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" /><span>Bedcover 3kg+ – <strong>Rp30.000</strong></span></label> <label class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" /><span>Sprei Set – <strong>Rp20.000</strong></span></label> <label class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" /><span>Sprei tanpa bantal – <strong>Rp15.000</strong></span></label> <label class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" /><span>Selimut bulu besar – <strong>Rp30.000</strong></span></label> <label class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" /><span>Selimut bulu sedang – <strong>Rp25.000</strong></span></label> <label class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" /><span>Boneka besar – <strong>Rp30.000</strong></span></label> <label class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" /><span>Boneka sedang – <strong>Rp15.000</strong></span></label> <label class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" /><span>Boneka kecil – <strong>Rp10.000</strong></span></label> </div> <div class="space-y-3"> <label class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" /><span>Seragam – <strong>Rp15.000/set</strong></span></label> <label class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" /><span>Dress – <strong>Rp25.000</strong></span></label> <label class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" /><span>Jas – <strong>Rp25.000</strong></span></label> <label class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" /><span>Handuk besar – <strong>Rp10.000</strong></span></label> <label class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" /><span>Handuk kecil – <strong>Rp5.000</strong></span></label> <label class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" /><span>Sepatu non-putih – <strong>Rp25.000</strong></span></label> <label class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" /><span>Sepatu putih – <strong>Rp30.000</strong></span></label> <label class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-green-600 rounded-full" /><span>Sepatu kulit – <strong>Rp25.000</strong></span></label> 
        </div> 
      </div> 
    </div> 
  </div> 
</section>

  <!-- Layanan Kami -->
  <section id="services" class="py-20 bg-gradient-to-b from-[#E0F4CB] via-[#D8F5D3] to-[#ACC1C6]">
    <div class="container mx-auto px-6 md:px-12">
      <h2 class="text-center text-3xl font-bold mb-12 text-gray-800">
        Layanan <span class="text-green-700">Kami</span>
      </h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="bg-white shadow-md rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300">
          <img src="/images/setrika.png" alt="Cuci Setrika" class="w-full h-48 object-cover">
          <p class="text-center font-semibold text-gray-800 py-4 text-lg">Cuci + Setrika</p>
        </div>

        <div class="bg-white shadow-md rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300">
          <img src="/images/celana.png" alt="Cuci Lipat" class="w-full h-48 object-cover">
          <p class="text-center font-semibold text-gray-800 py-4 text-lg">Cuci Lipat</p>
        </div>

        <div class="bg-white shadow-md rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300">
          <img src="/images/dryclean.png" alt="Dry Cleaning" class="w-full h-48 object-cover">
          <p class="text-center font-semibold text-gray-800 py-4 text-lg">Dry Cleaning</p>
        </div>

        <div class="bg-white shadow-md rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300">
          <img src="/images/mesincuci.png" alt="Express Service" class="w-full h-48 object-cover">
          <p class="text-center font-semibold text-gray-800 py-4 text-lg">Express Service</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer id="contact" class="bg-gradient-to-t from-[#ACC1C6] via-[#8DC78B] to-[#ACC1C6] text-white py-12">
    <div class="container mx-auto px-6 md:px-12">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center md:text-left">
        <div>
          <h3 class="font-semibold text-lg mb-2 border-b border-white/30 inline-block pb-1">Alamat</h3>
          <p class="text-sm leading-relaxed mt-2">Jl. Melati No.10, Bandung</p>
        </div>
        <div>
          <h3 class="font-semibold text-lg mb-2 border-b border-white/30 inline-block pb-1">Kontak</h3>
          <p class="text-sm mt-2">📞 0812-3456-7890</p>
          <p class="text-sm">✉️ timelaundry@email.com</p>
        </div>
        <div>
          <h3 class="font-semibold text-lg mb-2 border-b border-white/30 inline-block pb-1">Jam Operasional</h3>
          <p class="text-sm leading-relaxed mt-2">Setiap hari</p>
          <p class="text-sm">08.00 - 21.00 WIB</p>
        </div>
      </div>
      <div class="border-t border-white/20 mt-10 pt-4 text-center text-sm text-white/80">
        © 2025 <span class="font-semibold text-white">Time Laundry</span> – Hemat waktu, hidup lebih mudah.
      </div>
    </div>
  </footer>

</body>
</html>
