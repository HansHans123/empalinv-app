<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventaris Empal Gentong</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Logo dan Judul -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-lg mb-4">
                    <i class="fas fa-utensils text-3xl text-orange-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-white">Sistem Inventaris</h1>
                <p class="text-white/80 mt-2">UMKM Empal Gentong Cirebon</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Login ke Sistem</h2>
                
                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <!-- Username -->
                    <div class="mb-4">
                        <label for="username" class="block text-gray-700 text-sm font-medium mb-2">
                            <i class="fas fa-user mr-2"></i>Username
                        </label>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               value="{{ old('username') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition"
                               placeholder="Masukkan username"
                               required>
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-gray-700 text-sm font-medium mb-2">
                            <i class="fas fa-lock mr-2"></i>Password
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition"
                               placeholder="Masukkan password"
                               required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </button>
                </form>

                <!-- Demo Credentials -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-600 mb-2">Login Demo:</h3>
                    <div class="space-y-1">
                        <div class="flex items-center text-sm">
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded mr-2">admin</span>
                            <span class="text-gray-500">→</span>
                            <span class="ml-2">Administrator</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded mr-2">kasir1</span>
                            <span class="text-gray-500">→</span>
                            <span class="ml-2">Kasir</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded mr-2">dapur1</span>
                            <span class="text-gray-500">→</span>
                            <span class="ml-2">Staf Dapur</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-2">
                            Password semua akun: <span class="font-mono">password</span>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        © 2024 Sistem Inventaris Empal Gentong<br>
                        <span class="text-xs text-gray-500">UMKM Kuliner Cirebon</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>