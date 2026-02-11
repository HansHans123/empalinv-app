@extends('layouts.app')

@section('title', 'Edit Bahan Baku - Sistem Inventaris Empal Gentong')

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
                    <i class="fas fa-edit mr-2"></i>Edit Bahan Baku
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Edit data bahan baku: {{ $bahanBaku->nama }}
                </p>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('bahan-baku.update', $bahanBaku->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Kode Bahan -->
                        <div>
                            <label for="kode_bahan" class="block text-sm font-medium text-gray-700">
                                Kode Bahan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kode_bahan" id="kode_bahan" 
                                   value="{{ old('kode_bahan', $bahanBaku->kode_bahan) }}"
                                   class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('kode_bahan') border-red-300 @enderror"
                                   required>
                            @error('kode_bahan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Bahan -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">
                                Nama Bahan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama" 
                                   value="{{ old('nama', $bahanBaku->nama) }}"
                                   class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('nama') border-red-300 @enderror"
                                   required>
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
                                <option value="kg" {{ old('satuan', $bahanBaku->satuan) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                <option value="gram" {{ old('satuan', $bahanBaku->satuan) == 'gram' ? 'selected' : '' }}>Gram (g)</option>
                                <option value="liter" {{ old('satuan', $bahanBaku->satuan) == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                                <option value="mililiter" {{ old('satuan', $bahanBaku->satuan) == 'mililiter' ? 'selected' : '' }}>Mililiter (mL)</option>
                                <option value="buah" {{ old('satuan', $bahanBaku->satuan) == 'buah' ? 'selected' : '' }}>Buah</option>
                                <option value="ikat" {{ old('satuan', $bahanBaku->satuan) == 'ikat' ? 'selected' : '' }}>Ikat</option>
                                <option value="bungkus" {{ old('satuan', $bahanBaku->satuan) == 'bungkus' ? 'selected' : '' }}>Bungkus</option>
                                <option value="kaleng" {{ old('satuan', $bahanBaku->satuan) == 'kaleng' ? 'selected' : '' }}>Kaleng</option>
                                <option value="batang" {{ old('satuan', $bahanBaku->satuan) == 'batang' ? 'selected' : '' }}>Batang</option>
                                <option value="lembar" {{ old('satuan', $bahanBaku->satuan) == 'lembar' ? 'selected' : '' }}>Lembar</option>
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
                                <option value="daging" {{ old('kategori', $bahanBaku->kategori) == 'daging' ? 'selected' : '' }}>Daging</option>
                                <option value="santan" {{ old('kategori', $bahanBaku->kategori) == 'santan' ? 'selected' : '' }}>Santan</option>
                                <option value="rempah" {{ old('kategori', $bahanBaku->kategori) == 'rempah' ? 'selected' : '' }}>Rempah</option>
                                <option value="bumbu" {{ old('kategori', $bahanBaku->kategori) == 'bumbu' ? 'selected' : '' }}>Bumbu</option>
                                <option value="lainnya" {{ old('kategori', $bahanBaku->kategori) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                                   value="{{ old('harga_beli', $bahanBaku->harga_beli) }}" step="100" min="0"
                                   class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('harga_beli') border-red-300 @enderror"
                                   required>
                            @error('harga_beli')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stok Minimum -->
                        <div>
                            <label for="stok_minimum" class="block text-sm font-medium text-gray-700">
                                Stok Minimum <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="stok_minimum" id="stok_minimum" 
                                   value="{{ old('stok_minimum', $bahanBaku->stok_minimum) }}" step="0.01" min="0"
                                   class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('stok_minimum') border-red-300 @enderror"
                                   required>
                            @error('stok_minimum')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" 
                                    class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('status') border-red-300 @enderror" required>
                                <option value="aktif" {{ old('status', $bahanBaku->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $bahanBaku->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Current Stock Display -->
                <div class="px-4 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700">Stok Saat Ini</h3>
                            <p class="mt-1 text-2xl font-bold {{ $bahanBaku->stok <= $bahanBaku->stok_minimum ? 'text-red-600' : 'text-gray-900' }}">
                                {{ number_format($bahanBaku->stok, 2) }} {{ $bahanBaku->satuan }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700">Status Stok</h3>
                            <p class="mt-1">
                                @if($bahanBaku->stok <= 0)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Habis</span>
                                @elseif($bahanBaku->stok <= $bahanBaku->stok_minimum)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Rendah</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aman</span>
                                @endif
                            </p>
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
                        <i class="fas fa-save mr-2"></i>Perbarui Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection