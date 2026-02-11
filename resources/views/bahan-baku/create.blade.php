@extends('layouts.app')

@section('title', 'Tambah Bahan Baku - Sistem Inventaris Empal Gentong')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('bahan-baku.index') }}" class="inline-flex items-center text-sm font-medium text-orange-600 hover:text-orange-800">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Bahan Baku
            </a>
        </div>

        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Bahan Baku Baru
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Tambahkan bahan baku baru untuk produksi Empal Gentong
                </p>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('bahan-baku.store') }}" method="POST">
                @csrf
                
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Kode Bahan -->
                        <div>
                            <label for="kode_bahan" class="block text-sm font-medium text-gray-700">
                                Kode Bahan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kode_bahan" id="kode_bahan" 
                                   value="{{ old('kode_bahan') }}"
                                   class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('kode_bahan') border-red-300 @enderror"
                                   placeholder="Contoh: BB001" required>
                            @error('kode_bahan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Kode unik untuk identifikasi bahan baku</p>
                        </div>

                        <!-- Nama Bahan -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">
                                Nama Bahan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama" 
                                   value="{{ old('nama') }}"
                                   class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('nama') border-red-300 @enderror"
                                   placeholder="Contoh: Daging Sapi" required>
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Satuan -->
                        <div>
                            <label for="satuan" class="block text-sm font-medium text-gray-700">
                                Satuan <span class="text-red-500">*</span>
                            </label>
                            <select name="satuan" id="satuan" 
                                    class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('satuan') border-red-300 @enderror" required>
                                <option value="">Pilih Satuan</option>
                                <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                <option value="gram" {{ old('satuan') == 'gram' ? 'selected' : '' }}>Gram (g)</option>
                                <option value="liter" {{ old('satuan') == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                                <option value="mililiter" {{ old('satuan') == 'mililiter' ? 'selected' : '' }}>Mililiter (mL)</option>
                                <option value="buah" {{ old('satuan') == 'buah' ? 'selected' : '' }}>Buah</option>
                                <option value="ikat" {{ old('satuan') == 'ikat' ? 'selected' : '' }}>Ikat</option>
                                <option value="bungkus" {{ old('satuan') == 'bungkus' ? 'selected' : '' }}>Bungkus</option>
                                <option value="kaleng" {{ old('satuan') == 'kaleng' ? 'selected' : '' }}>Kaleng</option>
                                <option value="batang" {{ old('satuan') == 'batang' ? 'selected' : '' }}>Batang</option>
                                <option value="lembar" {{ old('satuan') == 'lembar' ? 'selected' : '' }}>Lembar</option>
                            </select>
                            @error('satuan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="kategori" class="block text-sm font-medium text-gray-700">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="kategori" id="kategori" 
                                    class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('kategori') border-red-300 @enderror" required>
                                <option value="">Pilih Kategori</option>
                                <option value="daging" {{ old('kategori') == 'daging' ? 'selected' : '' }}>Daging</option>
                                <option value="santan" {{ old('kategori') == 'santan' ? 'selected' : '' }}>Santan</option>
                                <option value="rempah" {{ old('kategori') == 'rempah' ? 'selected' : '' }}>Rempah</option>
                                <option value="bumbu" {{ old('kategori') == 'bumbu' ? 'selected' : '' }}>Bumbu</option>
                                <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kategori')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Harga Beli -->
                        <div>
                            <label for="harga_beli" class="block text-sm font-medium text-gray-700">
                                Harga Beli (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="harga_beli" id="harga_beli" 
                                   value="{{ old('harga_beli', 0) }}" step="100" min="0"
                                   class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('harga_beli') border-red-300 @enderror"
                                   required>
                            @error('harga_beli')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Harga beli per satuan</p>
                        </div>

                        <!-- Stok Minimum -->
                        <div>
                            <label for="stok_minimum" class="block text-sm font-medium text-gray-700">
                                Stok Minimum <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="stok_minimum" id="stok_minimum" 
                                   value="{{ old('stok_minimum', 0) }}" step="0.01" min="0"
                                   class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('stok_minimum') border-red-300 @enderror"
                                   required>
                            @error('stok_minimum')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Peringatan akan muncul jika stok ≤ nilai ini</p>
                        </div>

                        <!-- Stok Awal -->
                        <div>
                            <label for="stok" class="block text-sm font-medium text-gray-700">
                                Stok Awal (Opsional)
                            </label>
                            <input type="number" name="stok" id="stok" 
                                   value="{{ old('stok', 0) }}" step="0.01" min="0"
                                   class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('stok') border-red-300 @enderror">
                            @error('stok')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Biarkan 0 jika tidak ada stok awal</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" 
                                    class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('status') border-red-300 @enderror" required>
                                <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <a href="{{ route('bahan-baku.index') }}" 
                       class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 ml-3">
                        <i class="fas fa-save mr-2"></i>Simpan Bahan Baku
                    </button>
                </div>
            </form>
        </div>

        <!-- Tips -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-blue-900 mb-2">
                <i class="fas fa-lightbulb mr-2"></i>Tips:
            </h3>
            <ul class="text-sm text-blue-700 list-disc list-inside">
                <li>Pastikan kode bahan unik untuk setiap bahan baku</li>
                <li>Set stok minimum sesuai kebutuhan produksi harian</li>
                <li>Gunakan kategori untuk memudahkan pengelompokan</li>
                <li>Status "nonaktif" akan menyembunyikan bahan dari menu pemilihan di POS</li>
            </ul>
        </div>
    </div>
</div>
@endsection