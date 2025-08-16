<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\PasswordResetOtpController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ============ PUBLIC ROUTES ============

// Root route - Direct to login (NO MORE WELCOME PAGE)
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Alternative: If you want to show a simple landing page for non-authenticated users
// Route::get('/', function () {
//     if (auth()->check()) {
//         return redirect()->route('dashboard');
//     }
//     return redirect()->route('login');
// })->name('home');

// ============ GUEST ROUTES (Authentication & Password Reset) ============
Route::middleware('guest')->group(function () {
    
    // ============ 🔐 PASSWORD RESET OTP ROUTES ============
    // Request OTP
    Route::get('/password-reset-otp', [PasswordResetOtpController::class, 'showRequestForm'])
        ->name('password.otp.request');
    Route::post('/password-reset-otp', [PasswordResetOtpController::class, 'sendOtp'])
        ->name('password.otp.send');

    // Verify OTP
    Route::get('/password-reset-otp/verify', [PasswordResetOtpController::class, 'showVerifyForm'])
        ->name('password.otp.verify.form');
    Route::post('/password-reset-otp/verify', [PasswordResetOtpController::class, 'verifyOtp'])
        ->name('password.otp.verify');

    // Reset Password with OTP
    Route::get('/password-reset-otp/reset', [PasswordResetOtpController::class, 'showResetForm'])
        ->name('password.otp.reset.form');
    Route::post('/password-reset-otp/reset', [PasswordResetOtpController::class, 'resetPassword'])
        ->name('password.otp.reset');

    // Resend OTP
    Route::post('/password-reset-otp/resend', [PasswordResetOtpController::class, 'resendOtp'])
        ->name('password.otp.resend');
        
    // ============ 📧 TRADITIONAL PASSWORD RESET ROUTES ============
    // These routes are handled by require __DIR__.'/auth.php' at the bottom
    // But we can add custom routes here if needed
    
});

// ============ DASHBOARD ROUTE ============
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ============ AUTHENTICATED ROUTES ============
Route::middleware('auth')->group(function () {
    
    // ============ PROFILE ROUTES ============
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
    
    // ============ DASHBOARD API ROUTES ============
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/refresh-cards', [DashboardController::class, 'refreshCards'])->name('refresh-cards');
        Route::get('/statistik-kategori', [DashboardController::class, 'statistikKategori'])->name('statistik-kategori');
        Route::get('/export', [DashboardController::class, 'exportData'])->name('export');
    });
    
    // ============ BARANG ROUTES ============
    Route::resource('barang', BarangController::class);
    Route::patch('/barang/{barang}/toggle-status', [BarangController::class, 'toggleStatus'])->name('barang.toggle-status');
    
    // ============ TRANSAKSI ROUTES ============
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        
        // Specific routes (must come BEFORE resource routes)
        
        // Barang Masuk routes
        Route::get('/barang-masuk', [TransaksiController::class, 'barangMasuk'])->name('barang-masuk');
        Route::get('/create-barang-masuk', [TransaksiController::class, 'createBarangMasuk'])->name('create-barang-masuk');
        Route::post('/store-barang-masuk', [TransaksiController::class, 'storeBarangMasuk'])->name('store-barang-masuk');
        
        // Barang Keluar routes
        Route::get('/barang-keluar', [TransaksiController::class, 'barangKeluar'])->name('barang-keluar');
        Route::get('/create-barang-keluar', [TransaksiController::class, 'createBarangKeluar'])->name('create-barang-keluar');
        Route::post('/store-barang-keluar', [TransaksiController::class, 'storeBarangKeluar'])->name('store-barang-keluar');
        
        // Export routes for barang keluar - HANYA PDF
        Route::get('/barang-keluar/export/pdf', [TransaksiController::class, 'barangKeluar'])->name('barang-keluar.export.pdf');
        
        // Laporan route
        Route::get('/laporan', [TransaksiController::class, 'laporan'])->name('laporan');
    });
    
    // Transaksi Resource Routes (AFTER specific routes)
    Route::resource('transaksi', TransaksiController::class);
    
    // ============ API ROUTES ============
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/barang/{id}', [TransaksiController::class, 'getBarangDetail'])->name('barang.detail');
    });
    
    // ============ USER MANAGEMENT ROUTES ============
    Route::prefix('users')->name('users.')->group(function () {
        // Resource routes
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        
        // Additional user routes
        Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
    });
    
});

// ============ ADMIN ROUTES (Optional - for future use) ============
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin-only routes can be added here
    // Route::get('/system-settings', [AdminController::class, 'systemSettings'])->name('system-settings');
    // Route::get('/fix-user-timestamps', [UserController::class, 'fixTimestamps'])->name('fix-timestamps');
    
    // ============ 📊 ADMIN OTP MANAGEMENT ============
    // Route::get('/otp-logs', [PasswordResetOtpController::class, 'adminLogs'])->name('otp.logs');
    // Route::delete('/otp-cleanup', [PasswordResetOtpController::class, 'adminCleanup'])->name('otp.cleanup');
});

// ============ API ROUTES (Optional - for mobile app or external integrations) ============
Route::prefix('api/v1')->middleware(['auth:sanctum'])->name('api.v1.')->group(function () {
    // API routes can be added here for mobile app integration
    // Route::post('/password-reset-otp/request', [PasswordResetOtpController::class, 'apiSendOtp'])->name('otp.send');
    // Route::post('/password-reset-otp/verify', [PasswordResetOtpController::class, 'apiVerifyOtp'])->name('otp.verify');
});

// ============ FALLBACK ROUTES ============

// Handle undefined routes - redirect to login if not authenticated, dashboard if authenticated
Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('dashboard')->with('error', 'Halaman yang Anda cari tidak ditemukan.');
    }
    return redirect()->route('login')->with('error', 'Halaman yang Anda cari tidak ditemukan. Silakan login terlebih dahulu.');
});

// ============ AUTHENTICATION ROUTES ============
// This includes login, register, email verification, traditional password reset, etc.
require __DIR__.'/auth.php';