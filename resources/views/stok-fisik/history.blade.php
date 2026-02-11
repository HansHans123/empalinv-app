@extends('layouts.app')

@section('title', 'Riwayat Opname Stok')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-history mr-2"></i>Riwayat Opname Stok
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Daftar pengecekan stok fisik yang telah Anda lakukan.
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('stok-fisik.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Tabel Riwayat -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bahan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Sistem</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Fisik</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Selisih</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">%</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($history as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->tanggal->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $item->bahanBaku->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($item->stok_sistem, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($item->stok_fisik, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $item->selisih < 0 ? 'text-red-600' : ($item->selisih > 0 ? 'text-green-600' : '') }}">
                                {{ $item->selisih > 0 ? '+' : '' }}{{ number_format($item->selisih, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $item->persentase_selisih > 5 ? 'text-red-600' : 'text-green-600' }}">
                                {{ number_format($item->persentase_selisih, 2) }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status == 'normal')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Normal
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Melebihi Toleransi
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                Belum ada riwayat opname.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t">
                {{ $history->links() }}
            </div>
        </div>
    </div>
</div>
@endsection