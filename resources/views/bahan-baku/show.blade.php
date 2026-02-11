@extends('layouts.app')

@section('title', 'Detail Bahan Baku - Sistem Inventaris Empal Gentong')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('bahan-baku.index') }}" class="inline-flex items-center text-sm font-medium text-orange-600 hover:text-orange-800">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Bahan Baku
            </a>
        </div>

        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-box mr-2"></i>Detail Bahan Baku
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Informasi lengkap bahan baku: {{ $bahanBaku->nama }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-2">
                <a href="{{ route('bahan-baku.edit', $bahanBaku->id) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <button onclick="showUpdateStokModal({{ $bahanBaku->id }}, '{{ $bahanBaku->nama }}')"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-arrow-up-down mr-2"></i>Update Stok
                </button>
                <form action="{{ route('bahan-baku.destroy', $bahanBaku->id) }}" 
                      method="POST" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus bahan baku ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Basic Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information Card -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-info-circle mr-2"></i>Informasi Dasar
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Kode Bahan</dt>
                                <dd class="mt-1 text-lg font-semibold text-blue-600">{{ $bahanBaku->kode_bahan }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Nama Bahan</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $bahanBaku->nama }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                                <dd class="mt-1">
                                    @php
                                        $kategoriColors = [
                                            'daging' => 'bg-red-100 text-red-800',
                                            'santan' => 'bg-yellow-100 text-yellow-800',
                                            'rempah' => 'bg-green-100 text-green-800',
                                            'bumbu' => 'bg-blue-100 text-blue-800',
                                            'lainnya' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $color = $kategoriColors[$bahanBaku->kategori] ?? $kategoriColors['lainnya'];
                                    @endphp
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $color }}">
                                        {{ ucfirst($bahanBaku->kategori) }}
                                    </span>
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Satuan</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $bahanBaku->satuan }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Harga Beli</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">
                                    Rp {{ number_format($bahanBaku->harga_beli, 0, ',', '.') }}
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    @if($bahanBaku->status == 'aktif')
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                            Nonaktif
                                        </span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Stock Information Card -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-chart-bar mr-2"></i>Informasi Stok
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Current Stock -->
                            <div class="text-center">
                                <div class="text-3xl font-bold {{ $bahanBaku->stok <= $bahanBaku->stok_minimum ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ number_format($bahanBaku->stok, 2) }}
                                </div>
                                <div class="text-sm text-gray-500 mt-1">Stok Saat Ini</div>
                                <div class="text-xs text-gray-400">{{ $bahanBaku->satuan }}</div>
                            </div>
                            
                            <!-- Minimum Stock -->
                            <div class="text-center">
                                <div class="text-3xl font-bold text-gray-900">
                                    {{ number_format($bahanBaku->stok_minimum, 2) }}
                                </div>
                                <div class="text-sm text-gray-500 mt-1">Stok Minimum</div>
                                <div class="text-xs text-gray-400">{{ $bahanBaku->satuan }}</div>
                            </div>
                            
                            <!-- Stock Status -->
                            <div class="text-center">
                                @if($bahanBaku->stok <= 0)
                                    <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-2"></i>Habis
                                    </div>
                                @elseif($bahanBaku->stok <= $bahanBaku->stok_minimum)
                                    <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>Rendah
                                    </div>
                                @else
                                    <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-2"></i>Aman
                                    </div>
                                @endif
                                <div class="text-sm text-gray-500 mt-2">Status Stok</div>
                            </div>
                        </div>
                        
                        <!-- Stock Progress Bar -->
                        <div class="mt-6">
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>0</span>
                                <span>Stok Minimum: {{ number_format($bahanBaku->stok_minimum, 2) }}</span>
                                <span>{{ number_format($bahanBaku->stok_minimum * 2, 2) }}+</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                @php
                                    $percentage = min(100, ($bahanBaku->stok / max($bahanBaku->stok_minimum * 2, 1)) * 100);
                                    $color = $bahanBaku->stok <= $bahanBaku->stok_minimum ? 'bg-red-600' : 'bg-green-600';
                                @endphp
                                <div class="{{ $color }} h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Additional Information -->
            <div class="space-y-6">
                <!-- System Information Card -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-database mr-2"></i>Informasi Sistem
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dibuat Pada</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $bahanBaku->created_at->format('d F Y H:i:s') }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $bahanBaku->updated_at->format('d F Y H:i:s') }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ID Sistem</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $bahanBaku->id }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-bolt mr-2"></i>Aksi Cepat
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="space-y-3">
                            <button onclick="showUpdateStokModal({{ $bahanBaku->id }}, '{{ $bahanBaku->nama }}')"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-plus-circle mr-2"></i>Tambah Stok
                            </button>
                            
                            <a href="{{ route('bahan-baku.edit', $bahanBaku->id) }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                <i class="fas fa-edit mr-2"></i>Edit Data
                            </a>
                            
                            <a href="{{ route('bahan-baku.index') }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                <i class="fas fa-list mr-2"></i>Lihat Semua
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Notes Card -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-blue-900 mb-2">
                        <i class="fas fa-sticky-note mr-2"></i>Catatan:
                    </h3>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li class="flex items-start">
                            <i class="fas fa-chevron-right text-xs mt-1 mr-2"></i>
                            <span>Stok akan otomatis berkurang saat transaksi penjualan</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-chevron-right text-xs mt-1 mr-2"></i>
                            <span>Update stok dapat dilakukan melalui menu ini atau pembelian</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-chevron-right text-xs mt-1 mr-2"></i>
                            <span>Status nonaktif akan menyembunyikan dari daftar pilihan</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Stok Modal (Same as index) -->
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
            location.reload();
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