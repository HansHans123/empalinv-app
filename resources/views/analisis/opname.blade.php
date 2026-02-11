@extends('layouts.app')

@section('title', 'Input Opname Stok Fisik')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('analisis.selisih') }}" class="inline-flex items-center text-sm font-medium text-orange-600 hover:text-orange-800">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Analisis Selisih
            </a>
        </div>

        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-clipboard-check mr-2"></i>Input Stok Fisik (Opname)
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Lakukan pengecekan stok fisik dan bandingkan dengan stok sistem. Sistem akan otomatis menghitung selisih.
                </p>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('analisis.opname.store') }}" method="POST">
                @csrf
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Tanggal -->
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700">
                                Tanggal Opname <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required
                                   class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <!-- Pilih Bahan -->
                        <div>
                            <label for="bahan_id" class="block text-sm font-medium text-gray-700">
                                Pilih Bahan Baku <span class="text-red-500">*</span>
                            </label>
                            <select name="bahan_id" id="bahan_id" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-md">
                                <option value="">-- Pilih Bahan --</option>
                                @foreach($bahan as $item)
                                    <option value="{{ $item->id }}" {{ old('bahan_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->kode_bahan }} - {{ $item->nama }} (Stok Sistem: {{ number_format($item->stok, 2) }} {{ $item->satuan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Stok Fisik -->
                        <div>
                            <label for="stok_fisik" class="block text-sm font-medium text-gray-700">
                                Stok Fisik <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" name="stok_fisik" id="stok_fisik" step="0.01" min="0" value="{{ old('stok_fisik') }}" required
                                       class="focus:ring-orange-500 focus:border-orange-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                                       placeholder="0.00">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm" id="satuan_text">kg</span>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500" id="info_stok_sistem"></p>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700">
                                Keterangan (Opsional)
                            </label>
                            <textarea name="keterangan" id="keterangan" rows="3"
                                      class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                      placeholder="Contoh: Evaporasi, tumpah, kesalahan catat, dll.">{{ old('keterangan') }}</textarea>
                        </div>

                        <!-- Kalkulasi Selisih (Preview) -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Preview Selisih</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-xs text-gray-500">Stok Sistem</span>
                                    <div class="text-lg font-bold" id="preview_stok_sistem">0</div>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Stok Fisik</span>
                                    <div class="text-lg font-bold" id="preview_stok_fisik">0</div>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Selisih</span>
                                    <div class="text-lg font-bold" id="preview_selisih">0</div>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Persentase</span>
                                    <div class="text-lg font-bold" id="preview_persen">0%</div>
                                </div>
                            </div>
                            <div id="warning_selisih" class="mt-2 hidden">
                                <span class="inline-flex items-center px-2.5 py-1.5 rounded-md text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Selisih melebihi toleransi (5%)!
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <a href="{{ route('analisis.selisih') }}" 
                       class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 ml-3">
                        <i class="fas fa-save mr-2"></i>Simpan Opname
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-blue-900 mb-2">
                <i class="fas fa-info-circle mr-2"></i>Informasi:
            </h3>
            <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                <li>Sistem akan otomatis menghitung selisih dan persentase.</li>
                <li>Batas toleransi selisih yang diizinkan adalah <strong>5%</strong>.</li>
                <li>Jika selisih melebihi 5%, status akan tercatat sebagai "Melebihi Toleransi" dan perlu evaluasi.</li>
                <li>Stok sistem akan diperbarui dengan nilai stok fisik yang dimasukkan.</li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const bahanData = @json($bahan->keyBy('id'));

    document.getElementById('bahan_id').addEventListener('change', function() {
        const bahanId = this.value;
        if (!bahanId) {
            document.getElementById('preview_stok_sistem').innerText = '0';
            document.getElementById('satuan_text').innerText = 'kg';
            document.getElementById('info_stok_sistem').innerText = '';
            return;
        }

        const bahan = bahanData[bahanId];
        const stokSistem = bahan.stok;
        const satuan = bahan.satuan;

        document.getElementById('satuan_text').innerText = satuan;
        document.getElementById('info_stok_sistem').innerHTML = `Stok sistem: <strong>${formatAngka(stokSistem)} ${satuan}</strong>`;
        document.getElementById('preview_stok_sistem').innerHTML = `${formatAngka(stokSistem)} ${satuan}`;
        
        hitungPreview();
    });

    document.getElementById('stok_fisik').addEventListener('input', function() {
        hitungPreview();
    });

    function hitungPreview() {
        const bahanId = document.getElementById('bahan_id').value;
        if (!bahanId) return;

        const bahan = bahanData[bahanId];
        const stokSistem = bahan.stok;
        const satuan = bahan.satuan;
        const stokFisik = parseFloat(document.getElementById('stok_fisik').value) || 0;

        document.getElementById('preview_stok_fisik').innerHTML = `${formatAngka(stokFisik)} ${satuan}`;
        
        const selisih = stokFisik - stokSistem;
        const persen = stokSistem > 0 ? Math.abs((selisih / stokSistem) * 100) : (selisih !== 0 ? 100 : 0);
        
        document.getElementById('preview_selisih').innerHTML = `${selisih > 0 ? '+' : ''}${formatAngka(selisih)} ${satuan}`;
        document.getElementById('preview_selisih').className = selisih < 0 ? 'text-lg font-bold text-red-600' : (selisih > 0 ? 'text-lg font-bold text-green-600' : 'text-lg font-bold text-gray-900');
        
        document.getElementById('preview_persen').innerHTML = `${persen.toFixed(2)}%`;
        document.getElementById('preview_persen').className = persen > 5 ? 'text-lg font-bold text-red-600' : 'text-lg font-bold text-green-600';

        const warning = document.getElementById('warning_selisih');
        if (persen > 5) {
            warning.classList.remove('hidden');
        } else {
            warning.classList.add('hidden');
        }
    }

    function formatAngka(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Trigger on page load if old value exists
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('bahan_id').value) {
            document.getElementById('bahan_id').dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
@endsection