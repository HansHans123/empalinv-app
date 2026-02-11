@extends('layouts.app')

@section('title', 'Stok Fisik - Staf Dapur')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-clipboard-check mr-2"></i>Pengecekan Stok Fisik
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Lakukan opname stok bahan baku secara berkala untuk menjaga akurasi data.
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('stok-fisik.history') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-history mr-2"></i>Riwayat Opname
                </a>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Toleransi selisih stok adalah <strong>5%</strong>. Selisih di atas batas akan ditandai sebagai "Melebihi Toleransi".
                    </p>
                </div>
            </div>
        </div>

        <!-- Daftar Bahan -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-boxes mr-2"></i>Daftar Bahan Baku Aktif
                </h3>
                <div>
                    <input type="text" id="searchBahan" placeholder="Cari bahan..." 
                           class="px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bahan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Sistem</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bahan as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600">{{ $item->kode_bahan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">{{ $item->kategori }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $item->stok <= $item->stok_minimum ? 'text-red-600 font-bold' : 'text-gray-900' }}">
                                {{ number_format($item->stok, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($item->stok_minimum, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->satuan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->stok <= 0)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Habis</span>
                                @elseif($item->stok <= $item->stok_minimum)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Rendah</span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aman</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('stok-fisik.create', $item->id) }}" 
                                   class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
                                    <i class="fas fa-clipboard-list mr-1"></i> Opname
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada bahan baku aktif.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Pencarian bahan
    document.getElementById('searchBahan').addEventListener('keyup', function() {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const kode = row.cells[0]?.textContent.toLowerCase();
            const nama = row.cells[1]?.textContent.toLowerCase();
            const kategori = row.cells[2]?.textContent.toLowerCase();
            
            if (kode.includes(keyword) || nama.includes(keyword) || kategori.includes(keyword)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection