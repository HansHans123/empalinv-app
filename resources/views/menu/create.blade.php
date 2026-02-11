@extends('layouts.app')

@section('title', 'Tambah Menu - Sistem Inventaris Empal Gentong')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('menu.index') }}" class="inline-flex items-center text-sm font-medium text-orange-600 hover:text-orange-800">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Menu Baru
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Tambahkan menu baru untuk penjualan Empal Gentong
                </p>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('menu.store') }}" method="POST">
                @csrf
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="kode_menu" class="block text-sm font-medium text-gray-700">
                                Kode Menu <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kode_menu" id="kode_menu" value="{{ old('kode_menu') }}"
                                   class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                   placeholder="Contoh: M001" required>
                            @error('kode_menu')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Kode unik untuk menu</p>
                        </div>

                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">
                                Nama Menu <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                   class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                   placeholder="Contoh: Empal Gentong Porsi Reguler" required>
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="harga" class="block text-sm font-medium text-gray-700">
                                Harga (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="harga" id="harga" value="{{ old('harga') }}"
                                   class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                   placeholder="25000" min="0" step="100" required>
                            @error('harga')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                                Deskripsi (Opsional)
                            </label>
                            <textarea name="deskripsi" id="deskripsi" rows="3"
                                      class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-md">
                                <option value="tersedia" {{ old('status', 'tersedia') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="habis" {{ old('status') == 'habis' ? 'selected' : '' }}>Habis</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <a href="{{ route('menu.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 ml-3">
                        <i class="fas fa-save mr-2"></i>Simpan Menu
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-blue-900 mb-2">
                <i class="fas fa-lightbulb mr-2"></i>Tips:
            </h3>
            <ul class="text-sm text-blue-700 list-disc list-inside">
                <li>Setelah menambah menu, segera tambahkan resep (komposisi bahan) di halaman edit menu</li>
                <li>Status "Habis" akan menyembunyikan menu dari POS</li>
            </ul>
        </div>
    </div>
</div>
@endsection