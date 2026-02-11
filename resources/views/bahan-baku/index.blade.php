@extends('layouts.app')

@section('title', 'Bahan Baku - Sistem Inventaris Empal Gentong')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-boxes mr-2"></i>Manajemen Bahan Baku
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola data bahan baku untuk produksi Empal Gentong
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('bahan-baku.create') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    <i class="fas fa-plus mr-2"></i>Tambah Bahan Baku
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-6">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <i class="fas fa-boxes text-white"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Bahan Baku</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $totalBahanBaku }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Status Aktif</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $totalAktif }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Stok Rendah</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $totalStokRendah }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Data</h3>
                <form method="GET" action="{{ route('bahan-baku.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                        <input type="text" name="search" id="search" 
                               value="{{ request('search') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                               placeholder="Kode, nama, atau kategori...">
                    </div>
                    
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select name="kategori" id="kategori" 
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-md">
                            <option value="all" {{ request('kategori') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                            @foreach($kategoriList as $kategori)
                                <option value="{{ $kategori }}" {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                    {{ ucfirst($kategori) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" 
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-md">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <div class="flex space-x-2 w-full">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                            <a href="{{ route('bahan-baku.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                <i class="fas fa-redo mr-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            @if($bahanBaku->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bahan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bahanBaku as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-blue-600">{{ $item->kode_bahan }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->nama }}</div>
                                    <div class="text-sm text-gray-500">Harga: Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-sm font-semibold {{ $item->stok <= $item->stok_minimum ? 'text-red-600' : 'text-gray-900' }}">
                                            {{ number_format($item->stok, 2) }}
                                        </div>
                                        @if($item->stok <= $item->stok_minimum)
                                        <span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Rendah</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">Min: {{ number_format($item->stok_minimum, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->satuan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $kategoriColors = [
                                            'daging' => 'bg-red-100 text-red-800',
                                            'santan' => 'bg-yellow-100 text-yellow-800',
                                            'rempah' => 'bg-green-100 text-green-800',
                                            'bumbu' => 'bg-blue-100 text-blue-800',
                                            'lainnya' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $color = $kategoriColors[$item->kategori] ?? $kategoriColors['lainnya'];
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                        {{ ucfirst($item->kategori) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->status == 'aktif')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('bahan-baku.show', $item->id) }}" 
                                           class="text-blue-600 hover:text-blue-900" 
                                           title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('bahan-baku.edit', $item->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-900" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="showUpdateStokModal({{ $item->id }}, '{{ $item->nama }}')"
                                                class="text-green-600 hover:text-green-900" 
                                                title="Update Stok">
                                            <i class="fas fa-arrow-up-down"></i>
                                        </button>
                                        <form action="{{ route('bahan-baku.destroy', $item->id) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus bahan baku ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900" 
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $bahanBaku->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-box-open text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data bahan baku</h3>
                    <p class="text-gray-500 mb-6">Mulai dengan menambahkan bahan baku baru.</p>
                    <a href="{{ route('bahan-baku.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                        <i class="fas fa-plus mr-2"></i>Tambah Bahan Baku Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Update Stok Modal -->
<div id="updateStokModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Update Stok</h3>
            <p class="text-sm text-gray-500 mt-1" id="modalBahanNama"></p>
        </div>
        <form id="updateStokForm" method="POST">
            @csrf
            <div class="px-6 py-4">
                <div class="mb-4">
                    <label for="tipe" class="block text-sm font-medium text-gray-700 mb-2">Tipe Update</label>
                    <select id="tipe" name="tipe" required 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                        <option value="masuk">Stok Masuk (+)</option>
                        <option value="keluar">Stok Keluar (-)</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                    <input type="number" id="jumlah" name="jumlah" step="0.01" min="0.01" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                           placeholder="0.00">
                </div>
                
                <div class="mb-4">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <input type="text" id="keterangan" name="keterangan"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                           placeholder="Contoh: Pembelian, Koreksi, dll">
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 text-right">
                <button type="button" onclick="closeUpdateStokModal()" 
                        class="mr-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Batal
                </button>
                <button type="submit" 
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Update Stok
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let currentBahanId = null;

function showUpdateStokModal(id, nama) {
    currentBahanId = id;
    document.getElementById('modalBahanNama').textContent = 'Bahan: ' + nama;
    document.getElementById('updateStokModal').classList.remove('hidden');
    document.getElementById('updateStokModal').classList.add('flex');
    document.getElementById('jumlah').focus();
}

function closeUpdateStokModal() {
    document.getElementById('updateStokModal').classList.add('hidden');
    document.getElementById('updateStokModal').classList.remove('flex');
    document.getElementById('updateStokForm').reset();
    currentBahanId = null;
}

// Handle form submission
document.getElementById('updateStokForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const url = `/bahan-baku/${currentBahanId}/update-stok`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeUpdateStokModal();
            location.reload(); // Reload page to update stock
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate stok');
    });
});

// Close modal when clicking outside
document.getElementById('updateStokModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUpdateStokModal();
    }
});
</script>
@endpush
@endsection