<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\PasswordResetOtpController;
use Illuminate\Support\Facades\Route;

// ============ AUTHENTICATED ROUTES WITH ROLE PROTECTION ============
Route::middleware('auth')->group(function () {
    
    // ============ DASHBOARD (All authenticated users) ============
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ============ PROFILE ROUTES (All authenticated users) ============
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
    
    // ============ DASHBOARD API ROUTES (All authenticated users) ============
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/refresh-cards', [DashboardController::class, 'refreshCards'])->name('refresh-cards');
        Route::get('/statistik-kategori', [DashboardController::class, 'statistikKategori'])->name('statistik-kategori');
        Route::get('/export', [DashboardController::class, 'exportData'])->name('export');
    });
    
    // ============ FIXED: BARANG ROUTES (Clear Separation) ============
    
    // MANAGEMENT ACCESS (Admin, Manager, Staff) - FULL CRUD
    Route::middleware('role:admin,manager,staff')->prefix('barang')->name('barang.')->group(function () {
        Route::get('/', [BarangController::class, 'index'])->name('index');
        Route::get('/create', [BarangController::class, 'create'])->name('create');
        Route::post('/', [BarangController::class, 'store'])->name('store');
        Route::get('/{barang}', [BarangController::class, 'show'])->name('show');
        Route::get('/{barang}/edit', [BarangController::class, 'edit'])->name('edit');
        Route::put('/{barang}', [BarangController::class, 'update'])->name('update');
        Route::delete('/{barang}', [BarangController::class, 'destroy'])->name('destroy');
        Route::patch('/{barang}/toggle-status', [BarangController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    // USER READ-ONLY ACCESS (Only if not management role)
    Route::middleware('role:user')->prefix('barang-view')->name('barang-view.')->group(function () {
        Route::get('/', [BarangController::class, 'index'])->name('index');
        Route::get('/{barang}', [BarangController::class, 'show'])->name('show');
    });
    
    // ============ TRANSAKSI ROUTES (CORRECT ORDER) ============
    
    // ✅ SPECIFIC ROUTES FIRST
    Route::get('/transaksi/laporan', [TransaksiController::class, 'laporan'])->name('transaksi.laporan');
    
    // ============ MANAGEMENT ACCESS ROUTES (Admin, Manager, Staff) ============
    Route::middleware('role:admin,manager,staff')->group(function () {
        
        // ============ BARANG MASUK ROUTES ============
        Route::get('/transaksi/barang-masuk', [TransaksiController::class, 'barangMasuk'])->name('transaksi.barang-masuk');
        Route::get('/transaksi/barang-masuk/create', [TransaksiController::class, 'createBarangMasuk'])->name('transaksi.create-barang-masuk');
        Route::post('/transaksi/barang-masuk/store', [TransaksiController::class, 'storeBarangMasuk'])->name('transaksi.store-barang-masuk');
        
        // ============ BARANG KELUAR ROUTES ============
        Route::get('/transaksi/barang-keluar', [TransaksiController::class, 'barangKeluar'])->name('transaksi.barang-keluar');
        Route::get('/transaksi/barang-keluar/create', [TransaksiController::class, 'createBarangKeluar'])->name('transaksi.create-barang-keluar');
        Route::post('/transaksi/barang-keluar/store', [TransaksiController::class, 'storeBarangKeluar'])->name('transaksi.store-barang-keluar');
        
        // Export PDF for barang keluar
        Route::get('/transaksi/barang-keluar/export/pdf', [TransaksiController::class, 'barangKeluar'])->name('transaksi.barang-keluar.export.pdf');
        
        // ============ GENERAL TRANSACTION ROUTES ============
        Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/transaksi/store', [TransaksiController::class, 'store'])->name('transaksi.store');
    });
    
    // ✅ GENERAL/PARAMETERIZED ROUTES LAST
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
    
    // ============ ADMIN + MANAGER ONLY ROUTES ============
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/transaksi/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
        Route::put('/transaksi/{transaksi}', [TransaksiController::class, 'update'])->name('transaksi.update');
        Route::delete('/transaksi/{transaksi}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
    });
    
    // ============ API ROUTES (All authenticated users) ============
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/barang/{id}', [TransaksiController::class, 'getBarangDetail'])->name('barang.detail');
    });
    
    // ============ USER MANAGEMENT ROUTES (Admin and Manager only) ============
    Route::middleware('role:admin,manager')->prefix('users')->name('users.')->group(function () {
        // Basic CRUD (Admin and Manager)
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        
        // Password reset (Admin and Manager)
        Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        
        // Admin-only routes
        Route::middleware('role:admin')->group(function () {
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/{user}/update-role', [UserController::class, 'updateRole'])->name('update-role');
        });
    });
});

// ============ ADMIN ROUTES (Admin only) ============
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // System configuration
    Route::get('/settings', function() {
        return view('admin.settings');
    })->name('settings');
    
    // System logs
    Route::get('/logs', function() {
        return view('admin.logs');
    })->name('logs');
    
    // User analytics
    Route::get('/analytics', function() {
        return view('admin.analytics');
    })->name('analytics');
    
    // System backup
    Route::get('/backup', function() {
        return view('admin.backup');
    })->name('backup');
});

// ============ MANAGER ROUTES (Manager and Admin) ============
Route::middleware(['auth', 'role:admin,manager'])->prefix('manager')->name('manager.')->group(function () {
    // Staff management
    Route::get('/staff', function() {
        return redirect()->route('users.index', ['role' => 'staff']);
    })->name('staff');
    
    // Reports
    Route::get('/reports', function() {
        return view('manager.reports');
    })->name('reports');
});

// ============ FALLBACK ROUTES ============
Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('dashboard')->with('error', 'Halaman yang Anda cari tidak ditemukan.');
    }
    return redirect()->route('login')->with('error', 'Halaman yang Anda cari tidak ditemukan. Silakan login terlebih dahulu.');
});

// ============ AUTHENTICATION ROUTES ============
require __DIR__.'/auth.php';