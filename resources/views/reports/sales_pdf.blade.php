<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan ({{ ucfirst($period) }})</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        h1 { text-align: center; color: #5f9233; font-size: 18pt; }
        .summary-card { border: 1px solid #ccc; padding: 10px; margin-bottom: 15px; display: inline-block; width: 30%; margin-right: 3%; background-color: #f9f9f9; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .table th { background-color: #5f9233; color: white; text-align: center; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        /* Tambahkan font-size 8pt untuk dompdf agar mudah dibaca */
    </style>
</head>
<body>

    <h1>Laporan Penjualan ({{ ucfirst($period) }})</h1>
    <p>Tanggal Cetak: {{ now()->format('d F Y H:i:s') }}</p>
    <hr>
    
    <div style="margin-top: 20px;">
        <div class="summary-card">
            <p style="font-size: 8pt; color: #666;">Total Pendapatan</p>
            <p style="font-weight: bold; font-size: 12pt;">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="summary-card">
            <p style="font-size: 8pt; color: #666;">Total Transaksi</p>
            <p style="font-weight: bold; font-size: 12pt;">{{ $totalTransactions }}</p>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Periode</th>
                <th style="width: 30%;">Jumlah Transaksi</th>
                <th style="width: 35%;">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($salesData as $data)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ $data['period'] }}</td>
                <td class="text-center">{{ $data['transactions'] }}</td>
                <td class="text-right">Rp{{ number_format($data['revenue'], 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada data untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>