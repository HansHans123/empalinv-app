@extends('layouts.app')

@section('title', 'Laporan Pengeluaran / Pembelian Bahan')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Pengeluaran (Pembelian Bahan)</h1>
                <p class="text-sm text-gray-500">Periode: {{ $startDate }} s/d {{ $endDate }}</p>
            </div>
        </div>

        <!-- Filter Periode -->
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
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </div>
                <div class="flex items-end">
                    <a href="{{ route('laporan.pengeluaran') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Statistik Ringkas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="text-sm text-gray-500">Total Pengeluaran</div>
                <div class="text-xl font-bold text-gray-900">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="text-sm text-gray-500">Jumlah Transaksi</div>
                <div class="text-xl font-bold text-gray-900">{{ $totalTransaksiBeli }}</div>
            </div>
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="text-sm text-gray-500">Total Qty Dibeli</div>
                <div class="text-xl font-bold text-gray-900">{{ number_format($totalItemBeli, 2) }} unit</div>
            </div>
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="text-sm text-gray-500">Rata-rata per Transaksi</div>
                <div class="text-xl font-bold text-gray-900">Rp {{ $totalTransaksiBeli > 0 ? number_format($totalPengeluaran / $totalTransaksiBeli, 0, ',', '.') : 0 }}</div>
            </div>
        </div>

        <!-- Ringkasan per Supplier & per Bahan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Per Supplier -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Pembelian per Supplier</h3>
                </div>
                <div class="p-4">
                    @if($perSupplier->count() > 0)
                        <div class="space-y-3">
                            @foreach($perSupplier as $supplier)
                                <div class="flex justify-between items-center">
                                    <span class="font-medium">{{ $supplier->supplier ?? 'Umum' }}</span>
                                    <div>
                                        <span class="text-sm text-gray-600">{{ $supplier->jumlah }}x</span>
                                        <span class="ml-3 font-semibold text-gray-900">Rp {{ number_format($supplier->total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    @php
                                        $max = $perSupplier->max('total');
                                        $width = $max > 0 ? ($supplier->total / $max) * 100 : 0;
                                    @endphp
                                    <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $width }}%;"></div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Tidak ada data pembelian.</p>
                    @endif
                </div>
            </div>
            <!-- Per Bahan -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Pembelian per Bahan</h3>
                </div>
                <div class="p-4">
                    @if($perBahan->count() > 0)
                        <div class="space-y-3 max-h-80 overflow-y-auto">
                            @foreach($perBahan as $bahan)
                                <div class="flex justify-between items-center">
                                    <span class="font-medium">{{ $bahan->bahanBaku->nama }}</span>
                                    <div>
                                        <span class="text-sm text-gray-600">{{ number_format($bahan->total_qty, 2) }} {{ $bahan->bahanBaku->satuan }}</span>
                                        <span class="ml-3 font-semibold text-gray-900">Rp {{ number_format($bahan->total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Tidak ada data pembelian.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabel Detail Pembelian -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 border-b flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Detail Pembelian Bahan</h3>
                <span class="text-sm text-gray-500">Total {{ $pembelian->total() }} transaksi</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bahan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga Satuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pembelian as $p)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600">{{ $p->kode_pembelian }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $p->tanggal->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $p->bahanBaku->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($p->jumlah, 2) }} {{ $p->bahanBaku->satuan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $p->supplier ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $p->user->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada data pembelian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t">
                {{ $pembelian->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection