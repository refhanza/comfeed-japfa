<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API routes untuk inventory management
Route::middleware('auth:sanctum')->group(function () {
    // Barang API routes
    Route::apiResource('barang', App\Http\Controllers\BarangController::class);
    
    // Transaksi API routes
    Route::apiResource('transaksi', App\Http\Controllers\TransaksiController::class);
    
    // User API routes
    Route::apiResource('users', App\Http\Controllers\UserController::class);
    
    // Dashboard API
    Route::get('dashboard/stats', [App\Http\Controllers\DashboardController::class, 'apiStats']);
});