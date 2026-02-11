@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Penjualan</h1>
                <p class="text-sm text-gray-500">Periode: {{ $startDate }} s/d {{ $endDate }}</p>
            </div>
        </div>

        <!-- Form Filter -->
        <div class="bg-white shadow rounded-lg p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="mt-1 block w-full border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="mt-1 block w-full border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Group By</label>
                    <select name="group_by" class="mt-1 block w-full border-gray-300 rounded-md">
                        <option value="day" {{ $groupBy == 'day' ? 'selected' : '' }}>Harian</option>
                        <option value="month" {{ $groupBy == 'month' ? 'selected' : '' }}>Bulanan</option>
                        <option value="year" {{ $groupBy == 'year' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistik Ringkas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="text-sm text-gray-500">Total Penjualan</div>
                <div class="text-xl font-bold text-gray-900">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="text-sm text-gray-500">Jumlah Transaksi</div>
                <div class="text-xl font-bold text-gray-900">{{ $totalTransaksi }}</div>
            </div>
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="text-sm text-gray-500">Total Porsi Terjual</div>
                <div class="text-xl font-bold text-gray-900">{{ $totalPorsi }}</div>
            </div>
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="text-sm text-gray-500">Rata-rata Transaksi</div>
                <div class="text-xl font-bold text-gray-900">Rp {{ number_format($rataTransaksi, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Grafik & Metode Pembayaran -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="font-medium mb-4">Tren Penjualan</h3>
                <canvas id="salesChart" style="height: 250px;"></canvas>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="font-medium mb-4">Metode Pembayaran</h3>
                <div class="space-y-3">
                    @foreach($pembayaran as $metode)
                    <div class="flex justify-between items-center">
                        <span class="capitalize">{{ $metode->pembayaran }}</span>
                        <span class="font-semibold">Rp {{ number_format($metode->total, 0, ',', '.') }}</span>
                        <span class="text-sm text-gray-600">{{ $metode->jumlah }} transaksi</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Tabel Transaksi -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-3 border-b">
                <h3 class="font-medium">Detail Transaksi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Kasir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Porsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transaksi as $t)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono">{{ $t->kode_transaksi }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $t->tanggal->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $t->kasir->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $t->jumlah_porsi }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">{{ $t->pembayaran }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t">
                {{ $transaksi->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
const labels = @json($chartData->pluck('periode'));
const totals = @json($chartData->pluck('total'));
new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Penjualan',
            data: totals,
            borderColor: '#f97316',
            backgroundColor: 'rgba(249,115,22,0.1)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endsection