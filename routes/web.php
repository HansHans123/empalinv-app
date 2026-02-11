<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

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
        Route::get('/bahan-baku', function () {
            return 'Halaman Bahan Baku (Admin Only)';
        })->name('bahan-baku.index');
        
        Route::get('/menu', function () {
            return 'Halaman Menu (Admin Only)';
        })->name('menu.index');
        
        Route::get('/laporan', function () {
            return 'Halaman Laporan (Admin Only)';
        })->name('laporan.index');
    });
    
    // POS Routes (Kasir only)
    Route::middleware(['kasir'])->group(function () {
        Route::get('/pos', function () {
            return 'Halaman POS (Kasir Only)';
        })->name('pos.index');
    });
    
    // Stok Fisik Routes (Staf Dapur only)
    Route::middleware(['staf_dapur'])->group(function () {
        Route::get('/stok-fisik', function () {
            return 'Halaman Stok Fisik (Staf Dapur Only)';
        })->name('stok-fisik.index');
    });
});