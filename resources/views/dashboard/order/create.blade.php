@extends('dashboard.layouts.main')

@section('title', 'Form Pemesanan Time Laundry')

@section('container')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-green-700 text-white text-center py-4">
            <h1 class="text-xl font-bold">Form Pemesanan Time Laundry</h1>
        </div>

        <form action="{{ route('order.store') }}" method="POST" class="p-6">
            @csrf

            <!-- Informasi Pelanggan -->
            <div class="mb-6">
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <label class="text-sm font-medium text-gray-700 w-32">Nama Lengkap</label>
                        <span class="text-gray-600">:</span>
                        <input 
                            type="text" 
                            name="customer_name" 
                            value="{{ old('name', auth()->user()->name ?? '') }}"
                            class="flex-1 px-4 py-2 border-2 border-green-500 rounded-lg focus:outline-none focus:border-green-600"
                            required
                        >
                    </div>
                </div>

                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <label class="text-sm font-medium text-gray-700 w-32">No. Hp</label>
                        <span class="text-gray-600">:</span>
                        <input 
                            type="tel" 
                            name="customer_phone" 
                            value="{{ old('phone') }}"
                            class="flex-1 px-4 py-2 border-2 border-green-500 rounded-lg focus:outline-none focus:border-green-600"
                            required
                        >
                    </div>
                </div>
            </div>

            <!-- Jenis Layanan -->
            <div class="border-4 border-green-500 rounded-lg p-4 mb-6">
                <h2 class="text-center font-bold text-gray-800 mb-4 bg-green-200 py-2 rounded">Pilih Layanan Laundry</h2>

                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="layanan[]" value="Reguler" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <span>Reguler (Rp5.000 / Kg)</span>
                    </label>

                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="layanan[]" value="Express" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <span>Express (Rp8.000 / Kg)</span>
                    </label>
                </div>
            </div>

            <!-- Sistem Pengantaran -->
            <div class="border-4 border-green-500 rounded-lg p-4 mb-6">
                <h2 class="text-center font-bold text-gray-800 mb-4 bg-green-200 py-2 rounded">Sistem Pengantaran</h2>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center gap-2">
                        <input 
                            type="radio" 
                            name="delivery_type" 
                            value="antar_jemput" 
                            class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500"
                            checked
                        >
                        <span class="text-sm">Antar Jemput</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input 
                            type="radio" 
                            name="delivery_type" 
                            value="pengantaran_pribadi"
                            class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500"
                        >
                        <span class="text-sm">Pengantaran Pribadi</span>
                    </label>
                </div>

                <!-- Alamat -->
                <div id="alamatSection" class="mt-4 space-y-3">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700 w-40">Alamat</label>
                        <span class="text-gray-600">:</span>
                        <div class="flex-1">
                            <input 
                                type="text" 
                                name="address" 
                                id="addressInput"
                                class="w-full px-4 py-2 border-2 border-green-500 rounded-lg focus:outline-none focus:border-green-600"
                                placeholder="Masukkan alamat lengkap Anda..."
                            >
                        </div>
                    </div>
            </div>

            <!-- Catatan -->
            <div class="border-4 border-green-500 rounded-lg p-4 mb-6">
                <h2 class="text-center font-bold text-gray-800 mb-4 bg-green-200 py-2 rounded">Catatan Khusus</h2>
                <textarea 
                    name="notes" 
                    rows="4" 
                    class="w-full px-4 py-2 border-2 border-green-500 rounded-lg focus:outline-none focus:border-green-600 resize-none"
                    placeholder="Tambahkan catatan khusus..."
                >{{ old('notes') }}</textarea>
            </div>

            <!-- Tombol Submit -->
            <div class="text-center">
                <button 
                    type="submit" 
                    id="submitButton"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-12 rounded-lg shadow-lg transition duration-200"
                >
                    Pesan Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const addressInput = document.getElementById('addressInput');
const addressAlert = document.getElementById('addressAlert');
const submitButton = document.getElementById('submitButton');

addressInput.addEventListener('input', () => {
    const address = addressInput.value.toLowerCase();
    const valid = address.includes('tuah karya') || address.includes('tuahkarya');
    if (address.length && !valid) {
        addressAlert.classList.remove('hidden');
        submitButton.disabled = true;
    } else {
        addressAlert.classList.add('hidden');
        submitButton.disabled = false;
    }
});
</script>
@endsection
