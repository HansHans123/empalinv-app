@extends('layouts.app')

@section('title', 'Laporan Stok Bahan Baku')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-boxes mr-2"></i>Laporan Stok Bahan Baku
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Status stok terkini, nilai stok, dan riwayat mutasi
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <span class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600">
                    <i class="fas fa-calendar-alt mr-2"></i>{{ now()->format('d F Y') }}
                </span>
            </div>
        </div>

        <!-- Filter & Statistik Ringkas -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Filter Card -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg p-4">
                    <h3 class="font-medium text-gray-900 mb-3">Filter Data</h3>
                    <form method="GET" action="{{ route('laporan.stok') }}">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tampilkan</label>
                                <select name="filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-md">
                                    <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>Semua Bahan</option>
                                    <option value="low" {{ $filter == 'low' ? 'selected' : '' }}>Stok Rendah (≤ Minimum)</option>
                                    <option value="out" {{ $filter == 'out' ? 'selected' : '' }}>Stok Habis (0)</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 active:bg-orange-900 focus:outline-none focus:border-orange-900 focus:ring ring-orange-300 disabled:opacity-25 transition">
                                <i class="fas fa-filter mr-2"></i> Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistik Ringkas -->
            <div class="lg:col-span-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-white shadow rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-md p-2">
                                <i class="fas fa-cubes text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Bahan Aktif</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $bahan->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white shadow rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-md p-2">
                                <i class="fas fa-coins text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Nilai Stok</p>
                                <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($totalNilaiStok, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan per Kategori -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-tags mr-2"></i>Ringkasan per Kategori
                </h3>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    @foreach($kategoriSummary as $kategori)
                    <div class="border rounded-lg p-3 text-center">
                        <div class="text-lg font-bold text-gray-900">{{ $kategori->jumlah }}</div>
                        <div class="text-xs text-gray-500 capitalize">{{ $kategori->kategori }}</div>
                        <div class="text-sm font-medium text-green-600 mt-1">Rp {{ number_format($kategori->nilai, 0, ',', '.') }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Tabel Stok -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-list mr-2"></i>Detail Stok Bahan Baku
                </h3>
                <span class="text-sm text-gray-500">Total {{ $bahan->count() }} bahan</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bahan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Stok</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bahan as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600">{{ $item->kode_bahan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">{{ $item->kategori }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $item->stok <= $item->stok_minimum ? 'text-red-600 font-bold' : 'text-gray-900' }}">
                                {{ number_format($item->stok, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($item->stok_minimum, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->satuan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                Rp {{ number_format($item->stok * $item->harga_beli, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->stok <= 0)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Habis
                                    </span>
                                @elseif($item->stok <= $item->stok_minimum)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Rendah
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Aman
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data bahan baku.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Riwayat Mutasi Stok -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-history mr-2"></i>Riwayat Mutasi Stok (50 Terakhir)
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bahan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Awal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Akhir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($mutasi as $m)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $m->bahanBaku->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $m->jumlah_pakai > 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $m->jumlah_pakai > 0 ? '-' : '+' }} {{ number_format(abs($m->jumlah_pakai), 2) }} {{ $m->bahanBaku->satuan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($m->stok_awal, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($m->stok_akhir, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $m->keterangan ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $m->user->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Belum ada mutasi stok.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection