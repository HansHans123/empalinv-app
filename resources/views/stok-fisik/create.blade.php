@extends('layouts.app')

@section('title', 'Opname Stok Fisik')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('stok-fisik.index') }}" class="inline-flex items-center text-sm font-medium text-orange-600 hover:text-orange-800">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-clipboard-check mr-2"></i>Input Stok Fisik
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Masukkan jumlah stok fisik hasil pengecekan langsung.
                </p>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('stok-fisik.store') }}" method="POST">
                @csrf
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6">
                        @if(isset($bahanList))
                        <!-- Pilih Bahan (jika tidak ada parameter id) -->
                        <div>
                            <label for="bahan_id" class="block text-sm font-medium text-gray-700">
                                Pilih Bahan Baku <span class="text-red-500">*</span>
                            </label>
                            <select name="bahan_id" id="bahan_id" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-md">
                                <option value="">-- Pilih Bahan --</option>
                                @foreach($bahanList as $b)
                                    <option value="{{ $b->id }}" {{ old('bahan_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->kode_bahan }} - {{ $b->nama }} (Stok: {{ number_format($b->stok, 2) }} {{ $b->satuan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <!-- Bahan sudah ditentukan via parameter -->
                        <input type="hidden" name="bahan_id" value="{{ $bahan->id }}">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bahan Baku</label>
                            <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                <span class="font-medium">{{ $bahan->kode_bahan }} - {{ $bahan->nama }}</span>
                                <span class="ml-3 text-sm text-gray-600">Stok sistem: {{ number_format($bahan->stok, 2) }} {{ $bahan->satuan }}</span>
                            </div>
                        </div>
                        @endif

                        <!-- Tanggal Opname -->
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700">
                                Tanggal Opname <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required
                                   class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <!-- Stok Fisik -->
                        <div>
                            <label for="stok_fisik" class="block text-sm font-medium text-gray-700">
                                Stok Fisik <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" name="stok_fisik" id="stok_fisik" step="0.01" min="0" 
                                       value="{{ old('stok_fisik') }}" required
                                       class="focus:ring-orange-500 focus:border-orange-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                                       placeholder="0.00">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm" id="satuan_text">
                                        @if(isset($bahan)) {{ $bahan->satuan }} @else kg @endif
                                    </span>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500" id="info_stok_sistem">
                                @if(isset($bahan))
                                Stok sistem: <strong>{{ number_format($bahan->stok, 2) }} {{ $bahan->satuan }}</strong>
                                @endif
                            </p>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700">
                                Keterangan (Opsional)
                            </label>
                            <textarea name="keterangan" id="keterangan" rows="3"
                                      class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                      placeholder="Contoh: Evaporasi, sisa produksi, kerusakan, dll.">{{ old('keterangan') }}</textarea>
                        </div>

                        <!-- Preview Selisih -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Preview Selisih</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-xs text-gray-500">Stok Sistem</span>
                                    <div class="text-lg font-bold" id="preview_stok_sistem">
                                        @if(isset($bahan)) {{ number_format($bahan->stok, 2) }} {{ $bahan->satuan }} @else 0 @endif
                                    </div>
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
                    <a href="{{ route('stok-fisik.index') }}" 
                       class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                <i class="fas fa-info-circle mr-2"></i>Panduan:
            </h3>
            <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                <li>Pastikan Anda telah menghitung stok fisik secara akurat.</li>
                <li>Sistem akan otomatis menghitung selisih dan persentase.</li>
                <li>Toleransi selisih yang diizinkan adalah <strong>5%</strong>.</li>
                <li>Jika selisih melebihi 5%, status akan tercatat "Melebihi Toleransi" dan perlu evaluasi.</li>
                <li>Stok sistem akan langsung diperbarui sesuai stok fisik yang diinput.</li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
    @if(isset($bahan))
    const stokSistem = {{ $bahan->stok }};
    const satuan = '{{ $bahan->satuan }}';
    @endif

    @if(isset($bahanList))
    // Load data bahan via AJAX jika memilih dari dropdown
    document.getElementById('bahan_id')?.addEventListener('change', function() {
        const bahanId = this.value;
        if (!bahanId) return;
        
        fetch(`/stok-fisik/get-stok/${bahanId}`)
            .then(res => res.json())
            .then(data => {
                window.stokSistem = data.stok;
                window.satuan = data.satuan;
                document.getElementById('satuan_text').innerText = data.satuan;
                document.getElementById('info_stok_sistem').innerHTML = `Stok sistem: <strong>${formatAngka(data.stok)} ${data.satuan}</strong>`;
                document.getElementById('preview_stok_sistem').innerHTML = `${formatAngka(data.stok)} ${data.satuan}`;
                hitungPreview();
            });
    });
    @endif

    document.getElementById('stok_fisik')?.addEventListener('input', hitungPreview);

    function hitungPreview() {
        if (typeof stokSistem === 'undefined') {
            document.getElementById('preview_stok_fisik').innerHTML = '0';
            return;
        }

        const stokFisik = parseFloat(document.getElementById('stok_fisik').value) || 0;
        document.getElementById('preview_stok_fisik').innerHTML = `${formatAngka(stokFisik)} ${satuan}`;
        
        const selisih = stokFisik - stokSistem;
        const persen = stokSistem > 0 ? Math.abs((selisih / stokSistem) * 100) : (selisih !== 0 ? 100 : 0);
        
        const selisihEl = document.getElementById('preview_selisih');
        selisihEl.innerHTML = `${selisih > 0 ? '+' : ''}${formatAngka(selisih)} ${satuan}`;
        selisihEl.className = selisih < 0 ? 'text-lg font-bold text-red-600' : (selisih > 0 ? 'text-lg font-bold text-green-600' : 'text-lg font-bold text-gray-900');
        
        const persenEl = document.getElementById('preview_persen');
        persenEl.innerHTML = `${persen.toFixed(2)}%`;
        persenEl.className = persen > 5 ? 'text-lg font-bold text-red-600' : 'text-lg font-bold text-green-600';

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

    // Inisialisasi jika bahan sudah dipilih
    @if(isset($bahan))
    hitungPreview();
    @endif
</script>
@endpush
@endsection