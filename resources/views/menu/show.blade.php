@extends('layouts.app')

@section('title', 'Detail Menu - Sistem Inventaris Empal Gentong')

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
                    <i class="fas fa-utensils mr-2"></i>{{ $menu->nama }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Kode: {{ $menu->kode_menu }} | {{ $menu->resep->count() }} bahan dalam resep
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-2">
                <a href="{{ route('menu.edit', $menu->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <form action="{{ route('menu.destroy', $menu->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri: Informasi Menu -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-info-circle mr-2"></i>Informasi Menu
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Kode Menu</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $menu->kode_menu }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Menu</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $menu->nama }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Harga</dt>
                                <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($menu->harga, 0, ',', '.') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    @if($menu->status == 'tersedia')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Tersedia</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Habis</span>
                                    @endif
                                </dd>
                            </div>
                            @if($menu->deskripsi)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $menu->deskripsi }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Info Sistem -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-clock mr-2"></i>Informasi Sistem
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dibuat Pada</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $menu->created_at->format('d F Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $menu->updated_at->format('d F Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Resep -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <i class="fas fa-clipboard-list mr-2"></i>Resep / Komposisi Bahan
                </h3>
            </div>
            @if($menu->resep->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bahan Baku</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah per Porsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Satuan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Tersedia</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($menu->resep as $resep)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $resep->bahanBaku->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $resep->bahanBaku->kode_bahan }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($resep->jumlah, 2) }}
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
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500">Belum ada resep untuk menu ini.</p>
                    <a href="{{ route('menu.edit', $menu->id) }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                        <i class="fas fa-plus mr-2"></i>Tambah Resep
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection