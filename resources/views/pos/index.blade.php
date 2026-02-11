@extends('layouts.app')

@section('title', 'Point of Sale - Empal Gentong')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <i class="fas fa-cash-register mr-2"></i>Point of Sale (POS)
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Kasir: <span class="font-semibold">{{ auth()->user()->name }}</span>
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <span class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600">
                    <i class="fas fa-clock mr-2"></i>{{ now()->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>

        <!-- Main POS Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri: Daftar Menu (Grid) -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-utensils mr-2"></i>Daftar Menu Tersedia
                        </h2>
                        <div class="relative">
                            <input type="text" id="searchMenu" placeholder="Cari menu..." 
                                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="menuGrid">
                        @foreach($menu as $item)
                        <div class="menu-item bg-white border rounded-lg p-4 hover:shadow-md transition cursor-pointer"
                             data-id="{{ $item->id }}"
                             data-nama="{{ $item->nama }}"
                             data-harga="{{ $item->harga }}">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-utensils text-2xl text-orange-600"></i>
                                </div>
                                <h3 class="font-medium text-gray-900">{{ $item->nama }}</h3>
                                <p class="text-sm text-gray-500">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                @php
                                    $bisaDijual = true;
                                    foreach($item->resep as $resep) {
                                        if($resep->bahanBaku->stok < $resep->jumlah) {
                                            $bisaDijual = false;
                                            break;
                                        }
                                    }
                                @endphp
                                @if(!$bisaDijual)
                                <span class="inline-block mt-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">
                                    Stok kurang
                                </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Keranjang Belanja -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg p-4 h-full flex flex-col">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-shopping-cart mr-2"></i>Keranjang
                    </h2>

                    <!-- Daftar Item di Keranjang -->
                    <div id="cartItems" class="flex-1 overflow-y-auto max-h-96 mb-4">
                        <!-- Akan diisi oleh JavaScript -->
                        <div class="text-center text-gray-500 py-8">
                            <i class="fas fa-shopping-basket text-4xl mb-2"></i>
                            <p>Keranjang masih kosong</p>
                        </div>
                    </div>

                    <!-- Ringkasan Total -->
                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span id="subtotal" class="font-medium">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">PPN (0%)</span>
                            <span id="ppn" class="font-medium">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span id="total" class="text-orange-600">Rp 0</span>
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="relative">
                                <input type="radio" name="pembayaran" value="tunai" class="sr-only peer" checked>
                                <div class="w-full text-center px-3 py-2 border rounded-lg text-sm peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 cursor-pointer hover:bg-gray-50">
                                    <i class="fas fa-money-bill-wave mr-1"></i>Tunai
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="pembayaran" value="debit" class="sr-only peer">
                                <div class="w-full text-center px-3 py-2 border rounded-lg text-sm peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 cursor-pointer hover:bg-gray-50">
                                    <i class="fas fa-credit-card mr-1"></i>Debit
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="pembayaran" value="qris" class="sr-only peer">
                                <div class="w-full text-center px-3 py-2 border rounded-lg text-sm peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 cursor-pointer hover:bg-gray-50">
                                    <i class="fas fa-qrcode mr-1"></i>QRIS
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan (opsional)</label>
                        <input type="text" id="catatan" placeholder="Contoh: Makan di tempat / bungkus"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    <!-- Tombol Proses -->
                    <button id="btnProses" 
                            class="mt-4 w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-check-circle mr-2"></i>Proses Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sukses -->
<div id="successModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
        <div class="text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-3xl text-green-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Transaksi Berhasil!</h3>
            <p id="successMessage" class="text-sm text-gray-500 mb-4"></p>
            <div class="flex justify-center space-x-3">
                <a href="#" id="btnCetakStruk" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                    <i class="fas fa-print mr-2"></i>Cetak Struk
                </a>
                <button onclick="closeSuccessModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ==================== DATA KERANJANG ====================
    let cart = [];

    // ==================== FUNGSI UTILITAS ====================
    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // ==================== FUNGSI KERANJANG ====================
    function addToCart(id, nama, harga) {
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            existingItem.jumlah += 1;
        } else {
            cart.push({
                id: id,
                nama: nama,
                harga: harga,
                jumlah: 1
            });
        }
        renderCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function updateQuantity(index, change) {
        const item = cart[index];
        item.jumlah += change;
        if (item.jumlah <= 0) {
            cart.splice(index, 1);
        }
        renderCart();
    }

    function clearCart() {
        cart = [];
        renderCart();
    }

    function renderCart() {
        const cartContainer = document.getElementById('cartItems');
        const subtotalElement = document.getElementById('subtotal');
        const totalElement = document.getElementById('total');
        const btnProses = document.getElementById('btnProses');

        if (cart.length === 0) {
            cartContainer.innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-shopping-basket text-4xl mb-2"></i>
                    <p>Keranjang masih kosong</p>
                </div>
            `;
            subtotalElement.textContent = formatRupiah(0);
            totalElement.textContent = formatRupiah(0);
            btnProses.disabled = true;
            return;
        }

        let html = '';
        let subtotal = 0;

        cart.forEach((item, index) => {
            const itemSubtotal = item.harga * item.jumlah;
            subtotal += itemSubtotal;

            html += `
                <div class="flex justify-between items-center mb-3 pb-3 border-b">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">${item.nama}</h4>
                        <p class="text-sm text-gray-500">${formatRupiah(item.harga)} x ${item.jumlah}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">${formatRupiah(itemSubtotal)}</p>
                        <div class="flex items-center justify-end mt-1">
                            <button onclick="updateQuantity(${index}, -1)" class="text-red-600 hover:text-red-800 mx-1">
                                <i class="fas fa-minus-circle"></i>
                            </button>
                            <span class="mx-1 text-sm">${item.jumlah}</span>
                            <button onclick="updateQuantity(${index}, 1)" class="text-green-600 hover:text-green-800 mx-1">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                            <button onclick="removeFromCart(${index})" class="text-gray-400 hover:text-red-600 ml-2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        cartContainer.innerHTML = html;
        subtotalElement.textContent = formatRupiah(subtotal);
        totalElement.textContent = formatRupiah(subtotal);
        btnProses.disabled = false;
    }

    // ==================== EVENT LISTENERS ====================
    document.addEventListener('DOMContentLoaded', function() {
        // Klik menu item
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                const id = parseInt(this.dataset.id);
                const nama = this.dataset.nama;
                const harga = parseInt(this.dataset.harga);
                
                // Cek apakah ada stok kurang
                const stokKurang = this.querySelector('.bg-red-100');
                if (stokKurang) {
                    alert('Menu ini tidak dapat ditambahkan karena stok bahan baku kurang!');
                    return;
                }
                
                addToCart(id, nama, harga);
            });
        });

        // Pencarian menu
        const searchInput = document.getElementById('searchMenu');
        searchInput.addEventListener('keyup', function() {
            const keyword = this.value.toLowerCase();
            const items = document.querySelectorAll('.menu-item');
            
            items.forEach(item => {
                const nama = item.dataset.nama.toLowerCase();
                if (nama.includes(keyword)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Proses transaksi
        document.getElementById('btnProses').addEventListener('click', function() {
            if (cart.length === 0) {
                alert('Keranjang masih kosong!');
                return;
            }

            const pembayaran = document.querySelector('input[name="pembayaran"]:checked').value;
            const catatan = document.getElementById('catatan').value;

            const items = cart.map(item => ({
                menu_id: item.id,
                jumlah: item.jumlah
            }));

            fetch('{{ route("pos.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    items: items,
                    pembayaran: pembayaran,
                    catatan: catatan
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Tampilkan modal sukses
                    document.getElementById('successMessage').innerHTML = 
                        `Transaksi ${data.kode_transaksi} sebesar ${formatRupiah(data.total)} berhasil diproses.`;
                    // Buat template URL dari route name
                    const strukUrlTemplate = "{{ route('pos.struk', ':id') }}";
                    document.getElementById('btnCetakStruk').href = strukUrlTemplate.replace(':id', data.id); // ini sama yang di atas juga fix buat cetak struk -hanip
                    document.getElementById('successModal').classList.remove('hidden');
                    document.getElementById('successModal').classList.add('flex');
                    
                    // Reset keranjang
                    clearCart();
                    document.getElementById('catatan').value = '';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan pada server');
            });
        });
    });

    function closeSuccessModal() {
        document.getElementById('successModal').classList.add('hidden');
        document.getElementById('successModal').classList.remove('flex');
    }
</script>
@endpush