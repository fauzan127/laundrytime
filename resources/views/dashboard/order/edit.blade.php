@extends('dashboard.layouts.main')

@section('title', 'Edit Order #{{ $order->id }}')

@section('container')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-green-700 text-white text-center py-4">
            <h1 class="text-xl font-bold">Edit Order #{{ $order->id }}</h1>
        </div>

        <form action="{{ route('order.update', $order->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <!-- Informasi Pelanggan -->
            <div class="mb-6">
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <label class="text-sm font-medium text-gray-700 w-32">Nama Lengkap</label>
                        <span class="text-gray-600">:</span>
                        <input
                            type="text"
                            name="customer_name"
                            value="{{ old('customer_name', $order->nama_pelanggan) }}"
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
                            value="{{ old('customer_phone', $order->no_hp) }}"
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
                        <input type="checkbox" name="layanan[]" value="Reguler" {{ in_array('Reguler', old('layanan', $order->layanan ?? [])) ? 'checked' : '' }} class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <span>Reguler (Rp5.000 / Kg)</span>
                    </label>

                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="layanan[]" value="Express" {{ in_array('Express', old('layanan', $order->layanan ?? [])) ? 'checked' : '' }} class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
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
                            {{ old('delivery_type', $order->jenis_pengantaran) == 'antar_jemput' ? 'checked' : '' }}
                            class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500"
                        >
                        <span class="text-sm">Antar Jemput</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input
                            type="radio"
                            name="delivery_type"
                            value="pengantaran_pribadi"
                            {{ old('delivery_type', $order->jenis_pengantaran) == 'pengantaran_pribadi' ? 'checked' : '' }}
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
                                value="{{ old('address', $order->address) }}"
                                class="w-full px-4 py-2 border-2 border-green-500 rounded-lg focus:outline-none focus:border-green-600"
                                placeholder="Masukkan alamat lengkap Anda..."
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            {{-- <div class="border-4 border-green-500 rounded-lg p-4 mb-6">
                <h2 class="text-center font-bold text-gray-800 mb-4 bg-green-200 py-2 rounded">Catatan Khusus</h2>
                <textarea
                    name="notes"
                    rows="4"
                    class="w-full px-4 py-2 border-2 border-green-500 rounded-lg focus:outline-none focus:border-green-600 resize-none"
                    placeholder="Tambahkan catatan khusus..."
                >{{ old('notes', $order->catatan) }}</textarea>
            </div> --}}

            <!-- Status -->
            <div class="border-4 border-green-500 rounded-lg p-4 mb-6">
                <h2 class="text-center font-bold text-gray-800 mb-4 bg-green-200 py-2 rounded">Status Order</h2>
                <select name="status" class="w-full px-4 py-2 border-2 border-green-500 rounded-lg focus:outline-none focus:border-green-600">
                    <option value="Pending" {{ old('status', $order->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Proses" {{ old('status', $order->status) == 'Proses' ? 'selected' : '' }}>Proses</option>
                    <option value="Selesai" {{ old('status', $order->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- Tombol Submit -->
            <div class="text-center">
                <button
                    type="submit"
                    id="submitButton"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-12 rounded-lg shadow-lg transition duration-200"
                >
                    Update Order
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const addressInput = document.getElementById('addressInput');
const submitButton = document.getElementById('submitButton');

addressInput.addEventListener('input', () => {
    const address = addressInput.value.toLowerCase();
    const valid = address.includes('tuah karya') || address.includes('tuahkarya');
    if (address.length && !valid) {
        submitButton.disabled = true;
    } else {
        submitButton.disabled = false;
    }
});
</script>
@endsection
