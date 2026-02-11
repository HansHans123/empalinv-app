<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $penjualan->kode_transaksi }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            body { margin: 0; padding: 0; background: white; }
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-sm mx-auto bg-white shadow-lg rounded-lg p-6">
        <!-- Kop Struk -->
        <div class="text-center border-b pb-3 mb-3">
            <h1 class="text-xl font-bold">Empal Gentong Cirebon</h1>
            <p class="text-sm text-gray-600">Jl. Empal Gentong No. 1, Cirebon</p>
            <p class="text-sm text-gray-600">Telp: (0231) 123456</p>
        </div>

        <!-- Info Transaksi -->
        <div class="text-sm mb-3">
            <div class="flex justify-between">
                <span>No. Transaksi</span>
                <span class="font-medium">{{ $penjualan->kode_transaksi }}</span>
            </div>
            <div class="flex justify-between">
                <span>Tanggal</span>
                <span>{{ $penjualan->tanggal->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Kasir</span>
                <span>{{ $penjualan->kasir->name }}</span>
            </div>
        </div>

        <!-- Daftar Item -->
        <div class="border-t border-b py-3 mb-3">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="text-left">Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penjualan->detailPenjualan as $detail)
                    <tr>
                        <td>{{ $detail->menu->nama }}</td>
                        <td class="text-center">{{ $detail->jumlah_porsi }}</td>
                        <td class="text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="space-y-1 text-sm mb-4">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Pembayaran</span>
                <span class="capitalize">{{ $penjualan->pembayaran }}</span>
            </div>
            <div class="flex justify-between text-lg font-bold">
                <span>Total</span>
                <span>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-gray-600 border-t pt-3">
            <p>Terima kasih telah berbelanja di Empal Gentong</p>
            <p>Selamat menikmati hidangan khas Cirebon</p>
            <p class="mt-2">= = = = = = = = = = = = = = = = = =</p>
        </div>

        <!-- Tombol Cetak -->
        <div class="flex justify-center mt-4 no-print">
            <button onclick="window.print()" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                <i class="fas fa-print mr-2"></i>Cetak Struk
            </button>
            <a href="{{ route('pos.index') }}" class="ml-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                Kembali
            </a>
        </div>
    </div>

    <script>
        // Auto print ketika halaman dibuka
        window.onload = function() {
            // Uncomment baris di bawah jika ingin auto print
            // window.print();
        }
    </script>
</body>
</html>