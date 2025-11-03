@extends('dashboard.layouts.main')

@section('title', 'Edit Order #{{ $order->id }}')

@section('container')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-green-700 text-white text-center py-4">
            <h1 class="text-xl font-bold">Edit Order #{{ $order->order_number }}</h1>
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
                            value="{{ old('customer_name', $order->customer_name) }}"
                            class="flex-1 px-4 py-2 border-2 border-green-500 rounded-lg focus:outline-none focus:border-green-600 @error('customer_name') border-red-500 @enderror"
                            required
                        >
                    </div>
                    @error('customer_name')
                        <p class="text-red-500 text-xs ml-36">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <label class="text-sm font-medium text-gray-700 w-32">No. Whatsapp</label>
                        <span class="text-gray-600">:</span>
                        <input
                            type="tel"
                            name="customer_phone"
                            value="{{ old('customer_phone', $order->customer_phone) }}"
                            class="flex-1 px-4 py-2 border-2 border-green-500 rounded-lg focus:outline-none focus:border-green-600 @error('customer_phone') border-red-500 @enderror"
                            required
                        >
                    </div>
                    @error('customer_phone')
                        <p class="text-red-500 text-xs ml-36">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Rincian Pesanan -->
            <div class="border-4 border-green-500 rounded-lg p-4 mb-6">
                <h2 class="text-center font-bold text-gray-800 mb-4 bg-green-200 py-2 rounded">Rincian Pesanan</h2>

                @error('items')
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ $message }}
                    </div>
                @enderror

                <!-- Total Harga Display -->
                <div class="bg-green-100 border border-green-500 rounded-lg p-4 mb-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-800">Total Harga:</span>
                        <span id="totalPriceDisplay" class="text-xl font-bold text-green-600">Rp 0</span>
                    </div>
                </div>

                <!-- Service Types (per Kg) -->
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-700 mb-3 bg-gray-100 px-3 py-2 rounded">Service Types (per Kg)</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Kolom Kiri -->
                        <div class="space-y-2">
                            @foreach($serviceTypes->where('name', '!=', 'Express')->take(2) as $service)
                                <div class="service-item">
                                    <label class="flex items-start gap-2 text-sm">
                                        <input
                                            type="checkbox"
                                            class="w-4 h-4 mt-1 text-green-600 border-gray-300 rounded focus:ring-green-500 service-checkbox"
                                            data-service-id="{{ $service->id }}"
                                            data-service-name="{{ $service->name }}"
                                            data-service-price="{{ $service->price_per_kg }}"
                                            {{ $order->items->where('service_type_id', $service->id)->isNotEmpty() ? 'checked' : '' }}
                                        >
                                        <span>{{ $service->name }} (Rp{{ number_format($service->price_per_kg, 0, ',', '.') }}/kg)</span>
                                    </label>
                                    <div class="weight-input ml-6 mt-2 {{ $order->items->where('service_type_id', $service->id)->isEmpty() ? 'hidden' : '' }}">
                                        <label class="text-xs text-gray-600">Berat (kg):</label>
                                        <input
                                            type="number"
                                            step="0.1"
                                            min="0.1"
                                            max="1000"
                                            class="weight-value w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:border-green-500"
                                            value="{{ $order->items->where('service_type_id', $service->id)->first()->weight ?? '' }}"
                                            placeholder="0.0"
                                        >
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Kolom Kanan - Express -->
                        <div class="space-y-2">
                            <h4 class="font-semibold text-gray-700">Express (per Kg)</h4>
                            @foreach($serviceTypes->where('name', 'Express') as $service)
                                <div class="service-item">
                                    <label class="flex items-start gap-2 text-sm">
                                        <input
                                            type="checkbox"
                                            class="w-4 h-4 mt-1 text-green-600 border-gray-300 rounded focus:ring-green-500 service-checkbox"
                                            data-service-id="{{ $service->id }}"
                                            data-service-name="{{ $service->name }}"
                                            data-service-price="{{ $service->price_per_kg }}"
                                            {{ $order->items->where('service_type_id', $service->id)->isNotEmpty() ? 'checked' : '' }}
                                        >
                                        <span>{{ $service->name }} (Rp{{ number_format($service->price_per_kg, 0, ',', '.') }}/kg)</span>
                                    </label>
                                    <div class="weight-input ml-6 mt-2 {{ $order->items->where('service_type_id', $service->id)->isEmpty() ? 'hidden' : '' }}">
                                        <label class="text-xs text-gray-600">Berat (kg):</label>
                                        <input
                                            type="number"
                                            step="0.1"
                                            min="0.1"
                                            max="1000"
                                            class="weight-value w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:border-green-500"
                                            value="{{ $order->items->where('service_type_id', $service->id)->first()->weight ?? '' }}"
                                            placeholder="0.0"
                                        >
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Clothing Types (per Item) -->
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-700 mb-3 bg-gray-100 px-3 py-2 rounded">Clothing Types (per Item)</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Kolom Kiri -->
                        <div class="space-y-2">
                            @foreach($clothingTypes->take(ceil($clothingTypes->count() / 2)) as $clothing)
                                <div class="clothing-item">
                                    <label class="flex items-start gap-2 text-sm">
                                        <input
                                            type="checkbox"
                                            class="w-4 h-4 mt-1 text-green-600 border-gray-300 rounded focus:ring-green-500 clothing-checkbox"
                                            data-clothing-id="{{ $clothing->id }}"
                                            data-clothing-name="{{ $clothing->name }}"
                                            data-clothing-price="{{ $clothing->additional_price }}"
                                            {{ $order->items->where('clothing_type_id', $clothing->id)->isNotEmpty() ? 'checked' : '' }}
                                        >
                                        <span>{{ $clothing->name }} – Rp{{ number_format($clothing->additional_price, 0, ',', '.') }}</span>
                                    </label>
                                    <div class="quantity-input ml-6 mt-2 {{ $order->items->where('clothing_type_id', $clothing->id)->isEmpty() ? 'hidden' : '' }}">
                                        <label class="text-xs text-gray-600">Jumlah:</label>
                                        <input
                                            type="number"
                                            min="1"
                                            max="1000"
                                            class="quantity-value w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:border-green-500"
                                            value="{{ $order->items->where('clothing_type_id', $clothing->id)->first()->weight ?? '1' }}"
                                            placeholder="1"
                                        >
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="space-y-2">
                            @foreach($clothingTypes->slice(ceil($clothingTypes->count() / 2)) as $clothing)
                                <div class="clothing-item">
                                    <label class="flex items-start gap-2 text-sm">
                                        <input
                                            type="checkbox"
                                            class="w-4 h-4 mt-1 text-green-600 border-gray-300 rounded focus:ring-green-500 clothing-checkbox"
                                            data-clothing-id="{{ $clothing->id }}"
                                            data-clothing-name="{{ $clothing->name }}"
                                            data-clothing-price="{{ $clothing->additional_price }}"
                                            {{ $order->items->where('clothing_type_id', $clothing->id)->isNotEmpty() ? 'checked' : '' }}
                                        >
                                        <span>{{ $clothing->name }} – Rp{{ number_format($clothing->additional_price, 0, ',', '.') }}</span>
                                    </label>
                                    <div class="quantity-input ml-6 mt-2 {{ $order->items->where('clothing_type_id', $clothing->id)->isEmpty() ? 'hidden' : '' }}">
                                        <label class="text-xs text-gray-600">Jumlah:</label>
                                        <input
                                            type="number"
                                            min="1"
                                            max="1000"
                                            class="quantity-value w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:border-green-500"
                                            value="{{ $order->items->where('clothing_type_id', $clothing->id)->first()->weight ?? '1' }}"
                                            placeholder="1"
                                        >
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
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
                            {{ old('delivery_type', $order->delivery_type) == 'antar_jemput' ? 'checked' : '' }}
                            class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500"
                        >
                        <span class="text-sm">Antar Jemput</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input
                            type="radio"
                            name="delivery_type"
                            value="pengantaran_pribadi"
                            {{ old('delivery_type', $order->delivery_type) == 'pengantaran_pribadi' ? 'checked' : '' }}
                            class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500"
                        >
                        <span class="text-sm">Pengantaran Pribadi</span>
                    </label>
                </div>

                <!-- Alamat & Tanggal (Only for Antar Jemput) -->
                <div id="alamatSection" class="mt-4 space-y-3 {{ old('delivery_type', $order->delivery_type) !== 'antar_jemput' ? 'hidden' : '' }}">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700 w-40">Alamat</label>
                        <span class="text-gray-600">:</span>
                        <div class="flex-1">
                            <input
                                type="text"
                                name="address"
                                id="addressInput"
                                value="{{ old('address', $order->address) }}"
                                class="w-full px-4 py-2 border-2 border-green-500 rounded-lg focus:outline-none focus:border-green-600 @error('address') border-red-500 @enderror"
                                placeholder="Masukkan alamat lengkap Anda..."
                            >
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="text-yellow-600">⚠️ Catatan:</span> Layanan antar jemput hanya tersedia di <span class="font-semibold">Kelurahan Tuah Karya</span>
                            </p>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Alert jika alamat tidak valid (JavaScript) -->
                    <div id="addressAlert" class="hidden bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">Maaf, alamat Anda berada di luar area layanan kami.</span>
                        </div>
                        <p class="text-sm mt-1 ml-7">Kami hanya melayani antar jemput di Kelurahan Tuah Karya. Silakan pilih "Pengantaran Pribadi" atau ubah alamat Anda.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700 w-40">Jam/ Tanggal Penjemputan</label>
                        <span class="text-gray-600">:</span>
                        <input
                            type="time"
                            name="pickup_time"
                            value="{{ old('pickup_time', $order->pickup_time ? $order->pickup_time : '') }}"
                            class="w-32 px-4 py-2 border-2 border-green-500 rounded-lg focus:outline-none focus:border-green-600 @error('pickup_time') border-red-500 @enderror"
                        >
                        <input
                            type="date"
                            name="pickup_date"
                            value="{{ old('pickup_date', $order->pickup_date ? $order->pickup_date->format('Y-m-d') : '') }}"
                            min="{{ date('Y-m-d') }}"
                            class="flex-1 px-4 py-2 border-2 border-green-500 rounded-lg focus:outline-none focus:border-green-600 @error('pickup_date') border-red-500 @enderror"
                        >
                    </div>
                    @error('pickup_time')
                        <p class="text-red-500 text-xs ml-44">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Catatan Khusus -->
            <div class="border-4 border-green-500 rounded-lg p-4 mb-6">
                <h2 class="text-center font-bold text-gray-800 mb-4 bg-green-200 py-2 rounded">Catatan Khusus</h2>
                <textarea
                    name="notes"
                    rows="4"
                    class="w-full px-4 py-2 border-2 border-green-500 rounded-lg focus:outline-none focus:border-green-600 resize-none"
                    placeholder="Tambahkan catatan khusus untuk pesanan Anda..."
                >{{ old('notes', $order->notes) }}</textarea>
            </div>

            <!-- Status Pembayaran & Tanggal Transaksi -->
            <div class="border-4 border-green-500 rounded-lg p-4 mb-6">
                <h2 class="text-center font-bold text-gray-800 mb-4 bg-green-200 py-2 rounded">
                    Informasi Pembayaran
                </h2>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Status Pembayaran -->
                    @if(Auth::user() && Auth::user()->role === 'admin')
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status Pembayaran
                        </label>
                        <select
                            name="payment_status"
                            id="payment_status"
                            class="w-full px-4 py-2 border-2 border-green-500 rounded-lg focus:outline-none focus:border-green-600 @error('payment_status') border-red-500 @enderror"
                            required
                        >
                            <option value="">-- Pilih Status --</option>
                            <option value="belum_bayar" {{ old('payment_status', $order->payment_status) == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="sudah_bayar" {{ old('payment_status', $order->payment_status) == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                        </select>
                        @error('payment_status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    <!-- Tanggal Transaksi -->
                    <div>
                        <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Transaksi
                        </label>
                        <input
                            type="date"
                            name="transaction_date"
                            id="transaction_date"
                            value="{{ old('transaction_date', $order->transaction_date ? $order->transaction_date->format('Y-m-d') : date('Y-m-d')) }}"
                            class="w-full px-4 py-2 border-2 border-green-500 rounded-lg focus:outline-none focus:border-green-600 @error('transaction_date') border-red-500 @enderror"
                            required
                        >
                        @error('transaction_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Hidden fields untuk items yang dipilih -->
            <div id="selectedItemsContainer"></div>

            <!-- Submit Button -->
            <div class="text-center">
                <button
                    type="submit"
                    id="submitButton"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-12 rounded-lg shadow-lg transition duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed"
                >
                    Update Order
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Collect selected services and clothing types
function updateSelectedItems() {
    const container = document.getElementById('selectedItemsContainer');
    container.innerHTML = '';

    let itemIndex = 0;
    let totalPrice = 0;

    // Get all checked service checkboxes
    const selectedServices = document.querySelectorAll('.service-checkbox:checked');
    const selectedClothing = document.querySelectorAll('.clothing-checkbox:checked');

    // Create separate items for each selected service
    selectedServices.forEach(serviceCheckbox => {
        const serviceId = serviceCheckbox.dataset.serviceId;
        const servicePrice = parseFloat(serviceCheckbox.dataset.servicePrice);
        const serviceItem = serviceCheckbox.closest('.service-item');
        const weightInput = serviceItem.querySelector('.weight-value');
        const weight = weightInput ? parseFloat(weightInput.value) || 1 : 1;

        // Calculate item price (only service price)
        const itemPrice = servicePrice * weight;
        totalPrice += itemPrice;

        // Create hidden inputs
        const itemHtml = `
            <input type="hidden" name="items[${itemIndex}][service_type_id]" value="${serviceId}">
            <input type="hidden" name="items[${itemIndex}][clothing_type_id]" value="">
            <input type="hidden" name="items[${itemIndex}][weight]" value="${weight}">
        `;

        container.insertAdjacentHTML('beforeend', itemHtml);
        itemIndex++;
    });

    // Create separate items for each selected clothing
    selectedClothing.forEach(clothingCheckbox => {
        const clothingId = clothingCheckbox.dataset.clothingId;
        const clothingPrice = parseFloat(clothingCheckbox.dataset.clothingPrice);
        const clothingItem = clothingCheckbox.closest('.clothing-item');
        const quantityInput = clothingItem.querySelector('.quantity-value');
        const quantity = quantityInput ? parseFloat(quantityInput.value) || 1 : 1;

        // Calculate item price (only clothing price)
        const itemPrice = clothingPrice * quantity;
        totalPrice += itemPrice;

        // Create hidden inputs
        const itemHtml = `
            <input type="hidden" name="items[${itemIndex}][service_type_id]" value="">
            <input type="hidden" name="items[${itemIndex}][clothing_type_id]" value="${clothingId}">
            <input type="hidden" name="items[${itemIndex}][weight]" value="${quantity}">
        `;

        container.insertAdjacentHTML('beforeend', itemHtml);
        itemIndex++;
    });

    // Update total price display
    document.getElementById('totalPriceDisplay').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
}

// Toggle weight input visibility
function toggleWeightInput(checkbox) {
    const serviceItem = checkbox.closest('.service-item');
    const weightInput = serviceItem.querySelector('.weight-input');
    if (checkbox.checked) {
        weightInput.classList.remove('hidden');
    } else {
        weightInput.classList.add('hidden');
    }
}

// Toggle quantity input visibility
function toggleQuantityInput(checkbox) {
    const clothingItem = checkbox.closest('.clothing-item');
    const quantityInput = clothingItem.querySelector('.quantity-input');
    if (checkbox.checked) {
        quantityInput.classList.remove('hidden');
    } else {
        quantityInput.classList.add('hidden');
    }
}

// Validasi alamat
function validateAddress() {
    const deliveryType = document.querySelector('input[name="delivery_type"]:checked').value;
    const addressInput = document.getElementById('addressInput');
    const addressAlert = document.getElementById('addressAlert');
    const submitButton = document.getElementById('submitButton');

    if (deliveryType === 'antar_jemput') {
        const address = addressInput.value.toLowerCase();
        const isValidArea = address.includes('tuah karya') ||
                           address.includes('tuahkarya') ||
                           address.includes('tuah-karya');

        if (address.length > 0 && !isValidArea) {
            // Alamat tidak valid
            addressAlert.classList.remove('hidden');
            addressInput.classList.remove('border-green-500');
            addressInput.classList.add('border-red-500');
            submitButton.disabled = true;
            return false;
        } else if (address.length > 0 && isValidArea) {
            // Alamat valid
            addressAlert.classList.add('hidden');
            addressInput.classList.remove('border-red-500');
            addressInput.classList.add('border-green-500');
            submitButton.disabled = false;
            return true;
        } else {
            // Belum diisi
            addressAlert.classList.add('hidden');
            addressInput.classList.remove('border-red-500');
            addressInput.classList.add('border-green-500');
            submitButton.disabled = false;
            return true;
        }
    } else {
        // Pengantaran pribadi, no validation needed
        addressAlert.classList.add('hidden');
        submitButton.disabled = false;
        return true;
    }
}

// Toggle alamat section based on delivery type
function toggleAlamatSection() {
    const deliveryType = document.querySelector('input[name="delivery_type"]:checked').value;
    const alamatSection = document.getElementById('alamatSection');
    const addressInput = document.getElementById('addressInput');
    const addressAlert = document.getElementById('addressAlert');
    const submitButton = document.getElementById('submitButton');

    if (deliveryType === 'antar_jemput') {
        alamatSection.classList.remove('hidden');
        addressInput.required = true;
        validateAddress();
    } else {
        alamatSection.classList.add('hidden');
        addressInput.required = false;
        addressInput.value = '';
        addressAlert.classList.add('hidden');
        submitButton.disabled = false;
    }
}

// Add event listeners
document.addEventListener('DOMContentLoaded', function() {
    const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
    const clothingCheckboxes = document.querySelectorAll('.clothing-checkbox');
    const deliveryRadios = document.querySelectorAll('input[name="delivery_type"]');
    const addressInput = document.getElementById('addressInput');

    // Service & clothing checkboxes
    serviceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            toggleWeightInput(this);
            updateSelectedItems();
        });
    });

    clothingCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            toggleQuantityInput(this);
            updateSelectedItems();
        });
    });

    // Weight and quantity inputs
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('weight-value') || e.target.classList.contains('quantity-value')) {
            updateSelectedItems();
        }
    });

    // Delivery type change
    deliveryRadios.forEach(radio => {
        radio.addEventListener('change', toggleAlamatSection);
    });

    // Address input validation
    addressInput.addEventListener('input', validateAddress);
    addressInput.addEventListener('blur', validateAddress);

    // Initialize on page load
    toggleAlamatSection();
    updateSelectedItems();

    // Initialize total price display
    const totalPriceDisplay = document.getElementById('totalPriceDisplay');
    if (totalPriceDisplay) {
        totalPriceDisplay.textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
    }
});
</script>
@endsection