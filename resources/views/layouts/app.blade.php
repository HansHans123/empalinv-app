<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Inventaris Empal Gentong')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    @if(auth()->check())
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-utensils text-2xl text-orange-500 mr-2"></i>
                        <span class="text-xl font-bold text-gray-800">Empal Gentong</span>
                        <span class="ml-2 px-2 py-1 text-xs bg-orange-100 text-orange-800 rounded-full">UMKM</span>
                    </div>
                    
                    <!-- Menu Items -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'border-orange-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                        
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('bahan-baku.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('bahan-baku.*') ? 'border-orange-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700">
                            <i class="fas fa-boxes mr-2"></i> Bahan Baku
                        </a>
                        @endif
                        
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('menu.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('menu.*') ? 'border-orange-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                            <i class="fas fa-utensils mr-2"></i> Menu
                        </a>
                        @endif
                        
                        {{-- ini menu buat laporan sama analisis --}}
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('laporan.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('laporan.*') ? 'border-orange-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                            <i class="fas fa-chart-bar mr-2"></i> Laporan
                        </a>

                        <!-- Dropdown atau submenu untuk analisis? Bisa dijadikan terpisah -->
                        <a href="{{ route('analisis.selisih') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('analisis.*') ? 'border-orange-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Analisis Selisih
                        </a>
                        @endif

                        @if(auth()->user()->isKasir())
                        <a href="{{ route('pos.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('pos.*') ? 'border-orange-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700">
                            <i class="fas fa-cash-register mr-2"></i> POS
                        </a>
                        @endif
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <div class="ml-3 relative">
                        <div class="flex items-center">
                            <div class="text-sm text-gray-700 mr-4">
                                <div class="font-medium">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none transition">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @endif
    
    <!-- Main Content -->
    <main class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Notifications -->
            @if(session('success'))
            <div id="notification" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <i class="fas fa-check-circle mr-2"></i>
                    </div>
                    <div>
                        <span class="font-bold">Sukses!</span>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    <button onclick="document.getElementById('notification').style.display='none'" class="ml-auto">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            @endif
            
            @if(session('error'))
            <div id="error-notification" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                    </div>
                    <div>
                        <span class="font-bold">Error!</span>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                    <button onclick="document.getElementById('error-notification').style.display='none'" class="ml-auto">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    @stack('scripts')
</body>
</html>