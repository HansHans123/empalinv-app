@extends('layouts.app')

@section('title', 'Edit Menu - Sistem Inventaris Empal Gentong')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('menu.index') }}" class="inline-flex items-center text-sm font-medium text-orange-600 hover:text-orange-800">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-edit mr-2"></i>Edit Menu: {{ $menu->nama }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Ubah informasi menu dan kelola resep bahan baku
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('menu.show', $menu->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-eye mr-2"></i>Lihat Detail
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri: Form Edit Menu -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-info-circle mr-2"></i>Informasi Menu
                        </h3>
                    </div>
                    <form action="{{ route('menu.update', $menu->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="kode_menu" class="block text-sm font-medium text-gray-700">Kode Menu *</label>
                                    <input type="text" name="kode_menu" id="kode_menu" value="{{ old('kode_menu', $menu->kode_menu) }}"
                                           class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                    @error('kode_menu')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Menu *</label>
                                    <input type="text" name="nama" id="nama" value="{{ old('nama', $menu->nama) }}"
                                           class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                    @error('nama')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="harga" class="block text-sm font-medium text-gray-700">Harga (Rp) *</label>
                                    <input type="number" name="harga" id="harga" value="{{ old('harga', $menu->harga) }}"
                                           class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" min="0" step="100" required>
                                    @error('harga')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                    <textarea name="deskripsi" id="deskripsi" rows="3"
                                              class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                                    <select name="status" id="status"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-md">
                                        <option value="tersedia" {{ old('status', $menu->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                        <option value="habis" {{ old('status', $menu->status) == 'habis' ? 'selected' : '' }}>Habis</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
                                <i class="fas fa-save mr-2"></i>Perbarui Menu
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Kolom Kanan: Informasi Sistem -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-clock mr-2"></i>Informasi Sistem
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dibuat Pada</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $menu->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $menu->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jumlah Resep</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $menu->resep->count() }} bahan</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Manajemen Resep -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-clipboard-list mr-2"></i>Resep / Komposisi Bahan
                </h3>
                <button type="button" onclick="openTambahResepModal()" 
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <i class="fas fa-plus mr-2"></i>Tambah Bahan
                </button>
            </div>

            @if($menu->resep->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bahan Baku</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah per Porsi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Satuan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Tersedia</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($menu->resep as $resep)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $resep->bahanBaku->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $resep->bahanBaku->kode_bahan }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ number_format($resep->jumlah, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $resep->satuan }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $bahan = $resep->bahanBaku;
                                        $cukup = $bahan->stok >= $resep->jumlah;
                                    @endphp
                                    <span class="text-sm {{ $cukup ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($bahan->stok, 2) }} {{ $bahan->satuan }}
                                    </span>
                                    @if(!$cukup)
                                        <span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Stok kurang</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <button onclick="openEditResepModal({{ $resep->id }}, '{{ $resep->bahanBaku->nama }}', {{ $resep->jumlah }}, '{{ $resep->satuan }}')"
                                                class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('menu.resep.destroy', [$menu->id, $resep->id]) }}" method="POST" class="inline" 
                                              onsubmit="return confirm('Yakin ingin menghapus bahan ini dari resep?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
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
            @else
                <div class="text-center py-12">
                    <i class="fas fa-clipboard text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada resep</h3>
                    <p class="text-gray-500 mb-4">Tambahkan bahan baku untuk menu ini.</p>
                    <button onclick="openTambahResepModal()" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                        <i class="fas fa-plus mr-2"></i>Tambah Bahan Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Tambah Resep -->
<div id="tambahResepModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Tambah Bahan ke Resep</h3>
            <p class="text-sm text-gray-500 mt-1">Menu: {{ $menu->nama }}</p>
        </div>
        <form action="{{ route('menu.resep.store', $menu->id) }}" method="POST">
            @csrf
            <div class="px-6 py-4">
                <div class="mb-4">
                    <label for="bahan_id" class="block text-sm font-medium text-gray-700 mb-2">Bahan Baku</label>
                    <select name="bahan_id" id="bahan_id" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                        <option value="">-- Pilih Bahan --</option>
                        @foreach($bahanBaku as $bahan)
                            <option value="{{ $bahan->id }}">{{ $bahan->nama }} ({{ $bahan->satuan }}) - Stok: {{ number_format($bahan->stok, 2) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah per Porsi</label>
                    <input type="number" name="jumlah" id="jumlah" step="0.01" min="0.01" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                           placeholder="0.00">
                </div>
                <div class="mb-4">
                    <label for="satuan" class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>
                    <input type="text" name="satuan" id="satuan" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                           placeholder="Contoh: kg, liter, batang">
                    <p class="mt-1 text-xs text-gray-500">Gunakan satuan yang sama dengan satuan bahan baku</p>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 text-right">
                <button type="button" onclick="closeTambahResepModal()" 
                        class="mr-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" 
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
                    Tambahkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Resep -->
<div id="editResepModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Edit Resep</h3>
            <p class="text-sm text-gray-500 mt-1" id="editResepBahanNama"></p>
        </div>
        <form id="editResepForm" method="POST">
            @csrf
            @method('PUT')
            <div class="px-6 py-4">
                <div class="mb-4">
                    <label for="edit_jumlah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah per Porsi</label>
                    <input type="number" name="jumlah" id="edit_jumlah" step="0.01" min="0.01" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="edit_satuan" class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>
                    <input type="text" name="satuan" id="edit_satuan" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 text-right">
                <button type="button" onclick="closeEditResepModal()" 
                        class="mr-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" 
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openTambahResepModal() {
    document.getElementById('tambahResepModal').classList.remove('hidden');
    document.getElementById('tambahResepModal').classList.add('flex');
}

function closeTambahResepModal() {
    document.getElementById('tambahResepModal').classList.add('hidden');
    document.getElementById('tambahResepModal').classList.remove('flex');
}

function openEditResepModal(id, bahanNama, jumlah, satuan) {
    document.getElementById('editResepBahanNama').textContent = 'Bahan: ' + bahanNama;
    document.getElementById('edit_jumlah').value = jumlah;
    document.getElementById('edit_satuan').value = satuan;
    
    // Set form action
    let form = document.getElementById('editResepForm');
    form.action = `/menu/{{ $menu->id }}/resep/${id}`;
    
    document.getElementById('editResepModal').classList.remove('hidden');
    document.getElementById('editResepModal').classList.add('flex');
}

function closeEditResepModal() {
    document.getElementById('editResepModal').classList.add('hidden');
    document.getElementById('editResepModal').classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('tambahResepModal').addEventListener('click', function(e) {
    if (e.target === this) closeTambahResepModal();
});
document.getElementById('editResepModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditResepModal();
});
</script>
@endpush
@endsection