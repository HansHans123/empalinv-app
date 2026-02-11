@extends('layouts.app')

@section('title', 'Dashboard - Sistem Inventaris Empal Gentong')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
        
        <!-- Welcome Message -->
        <div class="mt-6 bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-orange-100 rounded-md p-3">
                        <i class="fas fa-utensils text-orange-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Selamat datang, {{ auth()->user()->name }}!</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Anda login sebagai <span class="font-semibold capitalize">{{ auth()->user()->role }}</span>.
                            Sistem Inventaris Empal Gentong siap digunakan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Role-based Content -->
        <div class="mt-6">
            @if(auth()->user()->isAdmin())
            <!-- Admin Dashboard -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-blue-900 mb-4">
                    <i class="fas fa-user-shield mr-2"></i>Fitur Administrator
                </h3>
                <ul class="space-y-3">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Kelola bahan baku</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Kelola menu dan resep</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Lihat laporan penjualan</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Analisis kinerja bisnis</span>
                    </li>
                </ul>
            </div>
            @endif
            
            @if(auth()->user()->isKasir())
            <!-- Kasir Dashboard -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-green-900 mb-4">
                    <i class="fas fa-cash-register mr-2"></i>Fitur Kasir
                </h3>
                <ul class="space-y-3">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Point of Sale (POS) System</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Transaksi penjualan</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Cetak struk transaksi</span>
                    </li>
                </ul>
            </div>
            @endif
            
            @if(auth()->user()->isStafDapur())
            <!-- Staf Dapur Dashboard -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-yellow-900 mb-4">
                    <i class="fas fa-clipboard-check mr-2"></i>Fitur Staf Dapur
                </h3>
                <ul class="space-y-3">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Pengecekan stok fisik</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Monitoring penggunaan bahan baku</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Deteksi selisih persediaan</span>
                    </li>
                </ul>
            </div>
            @endif
        </div>
        
        <!-- Quick Stats -->
        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Total Bahan Baku -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                            <i class="fas fa-boxes text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Bahan Baku
                            </dt>
                            <dd class="text-2xl font-semibold text-gray-900">
                                {{ \App\Models\BahanBaku::count() }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Menu -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <i class="fas fa-utensils text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Menu
                            </dt>
                            <dd class="text-2xl font-semibold text-gray-900">
                                {{ \App\Models\Menu::count() }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Pengguna -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                            <i class="fas fa-users text-purple-600"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Pengguna
                            </dt>
                            <dd class="text-2xl font-semibold text-gray-900">
                                {{ \App\Models\User::count() }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @if(auth()->user()->isAdmin())
                <a href="{{ route('bahan-baku.index') }}" class="bg-white overflow-hidden shadow rounded-lg hover:bg-gray-50 transition duration-150 ease-in-out">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-orange-100 rounded-md p-3">
                                <i class="fas fa-boxes text-orange-600"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Kelola Bahan Baku</h4>
                                <p class="mt-1 text-sm text-gray-500">Tambah, edit, atau hapus bahan baku</p>
                            </div>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('menu.index') }}" class="bg-white overflow-hidden shadow rounded-lg hover:bg-gray-50 transition duration-150 ease-in-out">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                <i class="fas fa-utensils text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Kelola Menu</h4>
                                <p class="mt-1 text-sm text-gray-500">Kelola menu dan resep</p>
                            </div>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('laporan.index') }}" class="bg-white overflow-hidden shadow rounded-lg hover:bg-gray-50 transition duration-150 ease-in-out">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                <i class="fas fa-chart-bar text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Lihat Laporan</h4>
                                <p class="mt-1 text-sm text-gray-500">Analisis penjualan dan stok</p>
                            </div>
                        </div>
                    </div>
                </a>
                @endif
                
                @if(auth()->user()->isKasir())
                <a href="{{ route('pos.index') }}" class="bg-white overflow-hidden shadow rounded-lg hover:bg-gray-50 transition duration-150 ease-in-out">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                <i class="fas fa-cash-register text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Point of Sale</h4>
                                <p class="mt-1 text-sm text-gray-500">Buat transaksi penjualan baru</p>
                            </div>
                        </div>
                    </div>
                </a>
                @endif
                
                @if(auth()->user()->isStafDapur())
                <a href="{{ route('stok-fisik.index') }}" class="bg-white overflow-hidden shadow rounded-lg hover:bg-gray-50 transition duration-150 ease-in-out">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                                <i class="fas fa-clipboard-check text-red-600"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Cek Stok Fisik</h4>
                                <p class="mt-1 text-sm text-gray-500">Lakukan pengecekan stok fisik</p>
                            </div>
                        </div>
                    </div>
                </a>
                @endif
            </div>
        </div>
        
        <!-- System Info -->
        <div class="mt-6 bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Informasi Sistem</h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Nama Aplikasi</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">Sistem Informasi Inventaris Empal Gentong</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Metode Pengembangan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">Waterfall Model</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Teknologi</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">Laravel, MySQL, Tailwind CSS</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection