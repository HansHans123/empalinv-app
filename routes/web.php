<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PosController;

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
        Route::resource('menu', MenuController::class);
        Route::post('menu/{menu}/resep', [MenuController::class, 'storeResep'])->name('menu.resep.store');
        Route::put('menu/{menu}/resep/{resep}', [MenuController::class, 'updateResep'])->name('menu.resep.update');
        Route::delete('menu/{menu}/resep/{resep}', [MenuController::class, 'destroyResep'])->name('menu.resep.destroy');
        });
    
    // POS Routes (Kasir only)
    Route::middleware(['auth', 'kasir'])->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
        Route::get('/pos/struk/{id}', [PosController::class, 'struk'])->name('pos.struk');
    });
    
    // Stok Fisik Routes (Staf Dapur only)
    Route::middleware(['staf_dapur'])->group(function () {
        Route::get('/stok-fisik', function () {
            return 'Halaman Stok Fisik (Coming Soon)';
        })->name('stok-fisik.index');
    });
});