@extends('layouts.app')

@section('title', 'Analisis Selisih Stok')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Analisis Selisih Stok
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Deteksi perbedaan antara stok sistem dan stok fisik, toleransi 5%
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-2">
                <a href="{{ route('analisis.opname') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                    <i class="fas fa-clipboard-check mr-2"></i>Input Opname Stok
                </a>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white shadow rounded-lg p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Selisih</label>
                    <select name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-md">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="normal" {{ $status == 'normal' ? 'selected' : '' }}>Normal (≤5%)</option>
                        <option value="melebihi_toleransi" {{ $status == 'melebihi_toleransi' ? 'selected' : '' }}>Melebihi Toleransi (>5%)</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    <a href="{{ route('analisis.selisih') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Statistik Selisih -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-md p-2">
                        <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Pengecekan</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalPengecekan }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 rounded-md p-2">
                        <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Selisih >5%</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalSelisihMelebihi }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-md p-2">
                        <i class="fas fa-percent text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Rata-rata Selisih</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($rataPersentase, 2) }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Tren Selisih -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-chart-line mr-2"></i>Tren Rata-rata Selisih Stok per Hari
                </h3>
            </div>
            <div class="p-4">
                <canvas id="selisihChart" style="height: 250px;"></canvas>
            </div>
        </div>

        <!-- Bahan dengan Selisih Tertinggi -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Bahan dengan Rata-rata Selisih Tertinggi</h3>
                </div>
                <div class="p-4">
                    @if($bahanTertinggi->count() > 0)
                        <div class="space-y-3">
                            @foreach($bahanTertinggi as $bahan)
                                <div class="flex justify-between items-center">
                                    <span class="font-medium">{{ $bahan->bahanBaku->nama }}</span>
                                    <span class="text-sm font-semibold {{ $bahan->rata_persen > 5 ? 'text-red-600' : 'text-yellow-600' }}">
                                        {{ number_format($bahan->rata_persen, 2) }}%
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada data selisih.</p>
                    @endif
                </div>
            </div>
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Interpretasi Selisih</h3>
                </div>
                <div class="p-4">
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                            <span><strong>Normal (≤5%)</strong> – Selisih masih dalam batas wajar, kemungkinan karena faktor teknis seperti evaporasi atau timbangan.</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                            <span><strong>Melebihi Toleransi (>5%)</strong> – Perlu investigasi: kesalahan pencatatan, pencurian, atau pemborosan.</span>
                        </div>
                        <p class="mt-2 text-gray-600 italic">
                            Rekomendasi: Lakukan opname stok secara rutin dan analisis penyebab selisih untuk perbaikan proses.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Detail Selisih -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 border-b flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Riwayat Pengecekan Stok Fisik</h3>
                <span class="text-sm text-gray-500">Total {{ $selisihData->total() }} data</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bahan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Sistem</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Fisik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Selisih</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">%</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($selisihData as $s)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $s->tanggal->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $s->bahanBaku->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($s->stok_sistem, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($s->stok_fisik, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $s->selisih < 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $s->selisih > 0 ? '+' : '' }}{{ number_format($s->selisih, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $s->persentase_selisih > 5 ? 'text-red-600' : 'text-green-600' }}">
                                {{ number_format($s->persentase_selisih, 2) }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($s->status == 'normal')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Normal
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Melebihi Toleransi
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $s->keterangan ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $s->user->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                Belum ada data pengecekan stok fisik.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t">
                {{ $selisihData->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('selisihChart').getContext('2d');
        const labels = @json($chartData->pluck('tanggal')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('d/m');
        }));
        const persen = @json($chartData->pluck('rata_persen'));
        const jumlah = @json($chartData->pluck('jumlah_cek'));

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Rata-rata Selisih (%)',
                        data: persen,
                        borderColor: 'rgb(249, 115, 22)',
                        backgroundColor: 'rgba(249, 115, 22, 0.1)',
                        yAxisID: 'y',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Jumlah Pengecekan',
                        data: jumlah,
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
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Persentase Selisih (%)'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                        title: {
                            display: true,
                            text: 'Jumlah'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection