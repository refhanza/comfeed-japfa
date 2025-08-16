<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\PasswordResetOtpController;
use Illuminate\Support\Facades\Route;

// ... existing public and guest routes ...

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
    
    // ============ BARANG ROUTES (Role-based access) ============
    Route::middleware('management')->group(function () {
        // Management can manage inventory
        Route::resource('barang', BarangController::class);
        Route::patch('/barang/{barang}/toggle-status', [BarangController::class, 'toggleStatus'])->name('barang.toggle-status');
    });
    
    // Read-only access for regular users
    Route::middleware('role:user')->group(function () {
        Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
        Route::get('/barang/{barang}', [BarangController::class, 'show'])->name('barang.show');
    });
    
    // ============ TRANSAKSI ROUTES (Role-based access) ============
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        
        // All authenticated users can view transactions (filtered by role in controller)
        Route::get('/', [TransaksiController::class, 'index'])->name('index');
        Route::get('/{transaksi}', [TransaksiController::class, 'show'])->name('show');
        Route::get('/laporan', [TransaksiController::class, 'laporan'])->name('laporan');
        
        // Management and staff can process transactions
        Route::middleware('role:admin,manager,staff')->group(function () {
            // Barang Masuk routes
            Route::get('/barang-masuk', [TransaksiController::class, 'barangMasuk'])->name('barang-masuk');
            Route::get('/create-barang-masuk', [TransaksiController::class, 'createBarangMasuk'])->name('create-barang-masuk');
            Route::post('/store-barang-masuk', [TransaksiController::class, 'storeBarangMasuk'])->name('store-barang-masuk');
            
            // Barang Keluar routes
            Route::get('/barang-keluar', [TransaksiController::class, 'barangKeluar'])->name('barang-keluar');
            Route::get('/create-barang-keluar', [TransaksiController::class, 'createBarangKeluar'])->name('create-barang-keluar');
            Route::post('/store-barang-keluar', [TransaksiController::class, 'storeBarangKeluar'])->name('store-barang-keluar');
            
            // Export routes for barang keluar
            Route::get('/barang-keluar/export/pdf', [TransaksiController::class, 'barangKeluar'])->name('barang-keluar.export.pdf');
            
            // Transaction CRUD
            Route::get('/create', [TransaksiController::class, 'create'])->name('create');
            Route::post('/', [TransaksiController::class, 'store'])->name('store');
        });
        
        // Only management can edit/delete transactions
        Route::middleware('admin.or.manager')->group(function () {
            Route::get('/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('edit');
            Route::put('/{transaksi}', [TransaksiController::class, 'update'])->name('update');
            Route::delete('/{transaksi}', [TransaksiController::class, 'destroy'])->name('destroy');
        });
    });
    
    // ============ API ROUTES (All authenticated users) ============
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/barang/{id}', [TransaksiController::class, 'getBarangDetail'])->name('barang.detail');
    });
    
    // ============ USER MANAGEMENT ROUTES (Admin and Manager only) ============
    Route::middleware('admin.or.manager')->prefix('users')->name('users.')->group(function () {
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
        Route::middleware('admin')->group(function () {
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/{user}/update-role', [UserController::class, 'updateRole'])->name('update-role');
        });
    });
});

// ============ ADMIN ROUTES (Admin only) ============
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
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