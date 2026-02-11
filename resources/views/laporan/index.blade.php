@extends('layouts.app')

@section('title', 'Dashboard Laporan & Analitik')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-chart-pie mr-2"></i>Dashboard Laporan & Analitik
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Ringkasan kinerja penjualan, stok, dan pengeluaran secara real-time
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <span class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600">
                    <i class="fas fa-calendar-alt mr-2"></i>{{ now()->format('d F Y') }}
                </span>
            </div>
        </div>

        <!-- Statistik Utama -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <i class="fas fa-shopping-cart text-white"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Penjualan Hari Ini</dt>
                                <dd class="text-lg font-medium text-gray-900">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</dd>
                                <dd class="text-xs text-gray-500">{{ $transaksiHariIni }} transaksi · {{ $totalMenuTerjual }} porsi</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Penjualan Bulan Ini</dt>
                                <dd class="text-lg font-medium text-gray-900">Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}</dd>
                                <dd class="text-xs text-gray-500">Total omzet bulan {{ now()->format('F') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <i class="fas fa-boxes text-white"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Nilai Stok</dt>
                                <dd class="text-lg font-medium text-gray-900">Rp {{ number_format($nilaiStok, 0, ',', '.') }}</dd>
                                <dd class="text-xs text-gray-500">{{ $totalBahan }} bahan · {{ $bahanStokRendah }} stok rendah</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                            <i class="fas fa-credit-card text-white"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pengeluaran Bulan Ini</dt>
                                <dd class="text-lg font-medium text-gray-900">Rp {{ number_format($totalPembelianBulanIni, 0, ',', '.') }}</dd>
                                <dd class="text-xs text-gray-500">Total pembelian bahan</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Penjualan 7 Hari -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-chart-bar mr-2"></i>Penjualan 7 Hari Terakhir
                </h3>
            </div>
            <div class="p-4">
                <canvas id="penjualanChart" style="height: 300px; width: 100%;"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Top 5 Menu Terlaris -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <i class="fas fa-utensils mr-2"></i>Top 5 Menu Terlaris
                    </h3>
                </div>
                <div class="p-4">
                    @if($topMenu->count() > 0)
                        <div class="space-y-3">
                            @foreach($topMenu as $item)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 text-center font-bold text-gray-600">{{ $loop->iteration }}</div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-gray-900">{{ $item->menu->nama }}</span>
                                            <span class="text-sm font-semibold text-green-600">{{ $item->total_terjual }} pcs</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                            @php
                                                $max = $topMenu->max('total_terjual');
                                                $width = $max > 0 ? ($item->total_terjual / $max) * 100 : 0;
                                            @endphp
                                            <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $width }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada data penjualan</p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <i class="fas fa-bolt mr-2"></i>Akses Cepat Laporan
                    </h3>
                </div>
                <div class="p-4 grid grid-cols-2 gap-3">
                    <a href="{{ route('laporan.penjualan') }}" class="inline-flex items-center px-4 py-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100">
                        <i class="fas fa-shopping-cart text-blue-600 mr-2"></i>
                        <span class="text-sm font-medium text-blue-800">Penjualan</span>
                    </a>
                    <a href="{{ route('laporan.stok') }}" class="inline-flex items-center px-4 py-3 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100">
                        <i class="fas fa-boxes text-green-600 mr-2"></i>
                        <span class="text-sm font-medium text-green-800">Stok</span>
                    </a>
                    <a href="{{ route('laporan.pengeluaran') }}" class="inline-flex items-center px-4 py-3 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100">
                        <i class="fas fa-credit-card text-yellow-600 mr-2"></i>
                        <span class="text-sm font-medium text-yellow-800">Pengeluaran</span>
                    </a>
                    <a href="{{ route('analisis.selisih') }}" class="inline-flex items-center px-4 py-3 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        <span class="text-sm font-medium text-red-800">Selisih Stok</span>
                    </a>
                    <a href="{{ route('analisis.opname') }}" class="inline-flex items-center px-4 py-3 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100">
                        <i class="fas fa-clipboard-check text-purple-600 mr-2"></i>
                        <span class="text-sm font-medium text-purple-800">Opname Stok</span>
                    </a>
                    <a href="#" class="inline-flex items-center px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-file-pdf text-gray-600 mr-2"></i>
                        <span class="text-sm font-medium text-gray-800">Export PDF</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Informasi Sistem -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-database mr-2"></i>Informasi Data
                </h3>
            </div>
            <div class="p-4 text-sm text-gray-600">
                <p>Semua laporan disajikan berdasarkan data real-time dari sistem. Filter dan ekspor tersedia di halaman masing-masing.</p>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('penjualanChart').getContext('2d');
    
    const labels = @json($penjualan7Hari->pluck('tanggal')->map(function($date) {
        return \Carbon\Carbon::parse($date)->format('d/m');
    }));
    const totals = @json($penjualan7Hari->pluck('total'));
    const transactions = @json($penjualan7Hari->pluck('transaksi'));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Total Penjualan (Rp)',
                    data: totals,
                    borderColor: 'rgb(249, 115, 22)',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    yAxisID: 'y',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Jumlah Transaksi',
                    data: transactions,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    yAxisID: 'y1',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Nominal (Rp)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false,
                    },
                    title: {
                        display: true,
                        text: 'Transaksi'
                    }
                }
            }
        }
    });
});
</script>
@endsection