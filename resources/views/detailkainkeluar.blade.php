<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Pesanan Laundry</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
<style>
    body { font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif; }
    .circle { border: 4px solid #ACC1C6; }
</style>
</head>
<body class="min-h-screen" style="background: linear-gradient(135deg, #9EC37D, #CEE1E7);">

<div class="mx-auto">
    <!-- Header -->
    <div class="w-full bg-[#518641] py-3 px-4 mb-8">
        <a href="{{ route('orders.index') }}" 
           class="text-3xl text-white font-light hover:text-gray-200">
            &lt;
        </a>
    </div>

    <div class="bg-white max-w-xl mx-auto p-4 sm:p-6 pt-0 mb-10 rounded-lg">

        <!-- Info Pesanan -->
        <div class="text-gray-800 text-base leading-relaxed mb-8 space-y-1">
            <p>ID Pesanan : TL{{ $data->id }}</p>
            <p>Nama Customer: {{ $data->customer_name }}</p>
            <p>Telepon: {{ $data->customer_phone ?? '-' }}</p>
            <p>Tanggal Masuk : {{ $data->created_at }}</p>
            <p>Layanan : {{ $data->delivery_type }}</p>
        </div>

        <!-- Detail Pesanan -->
        <h3 class="text-lg font-bold mb-3">Detail Pesanan</h3>
        <div class="overflow-x-auto border border-gray-200 rounded-lg mb-8">
            <table class="w-full text-sm text-left border-collapse rounded-lg" style="border: 10px solid white;">
                <thead>
                    <tr class="bg-[#518641] text-white font-medium">
                        <th class="px-4 py-2 border-r border-b border-[#518641]">Jenis Layanan</th>
                        <th class="px-4 py-2 border-r border-b border-[#518641] text-center">Berat</th>
                        <th class="px-4 py-2 border-b border-[#518641]">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-2 border-r">{{ $data->delivery_type }}</td>
                        <td class="px-4 py-2 border-r text-center">{{ $data->weight }}</td>
                        <td class="px-4 py-2">
                            <span class="font-semibold {{ $data->status }} px-2 py-1 rounded-full text-sm">
                                {{ $data->status }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Info Tambahan -->
        <div class="text-gray-800 text-base leading-relaxed space-y-1 mb-6">
            <p>Status Pesanan : <span class="font-semibold {{ $data->status }} px-2 py-1 rounded-full">{{ $data->status}}</span></p>
            <p>Tanggal Masuk : <span class="font-semibold">{{ $data->created_at }}</span></p>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex gap-3 mt-6">
            <a href="{{ route('orders.index') }}" 
               class="flex-1 bg-gray-500 hover:bg-gray-600 text-white text-center py-2 px-4 rounded-md transition-all">
                Kembali
            </a>
        </div>

    </div>
</div>

<script>



document.addEventListener("DOMContentLoaded", function() {
    const circles = document.querySelectorAll(".circle");

    // Map status to step index
    const statusMap = {
        'Diproses': 0,
        'Siap antar': 1,
        'Antar': 2,
        'Sampai tujuan': 3
    };

    function setActive(index) {
        circles.forEach((circle, i) => {
            if (i <= index) {
                circle.style.backgroundColor = "#5F9233";
            } else {
                circle.style.backgroundColor = "#D1D5DB";
            }
            circle.style.borderColor = "#ACC1C6";
        });
    }

    circles.forEach((circle, index) => {
        circle.addEventListener("click", () => setActive(index));
    });

    // Set status berdasarkan data transaksi
    const currentStatus = "{{ $data->status_layanan }}";
    const activeIndex = statusMap[currentStatus] || 0;
    setActive(activeIndex);
});

</script>

</body>
</html>