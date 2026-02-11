<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BahanBakuController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Bahan Baku Routes (Admin only)
    Route::middleware(['admin'])->group(function () {
        Route::resource('bahan-baku', BahanBakuController::class);
        Route::post('bahan-baku/{bahanBaku}/update-stok', [BahanBakuController::class, 'updateStok'])->name('bahan-baku.update-stok');
        Route::get('bahan-baku-export/pdf', [BahanBakuController::class, 'exportPDF'])->name('bahan-baku.export.pdf');
    });
    
    // POS Routes (Kasir only)
    Route::middleware(['kasir'])->group(function () {
        Route::get('/pos', function () {
            return 'Halaman POS (Coming Soon)';
        })->name('pos.index');
    });
    
    // Stok Fisik Routes (Staf Dapur only)
    Route::middleware(['staf_dapur'])->group(function () {
        Route::get('/stok-fisik', function () {
            return 'Halaman Stok Fisik (Coming Soon)';
        })->name('stok-fisik.index');
    });
});