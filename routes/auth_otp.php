<?php

use App\Http\Controllers\Auth\PasswordResetOtpController;

// Password Reset OTP Routes
Route::middleware('guest')->group(function () {
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

    // Reset Password
    Route::get('/password-reset-otp/reset', [PasswordResetOtpController::class, 'showResetForm'])
        ->name('password.otp.reset.form');
    Route::post('/password-reset-otp/reset', [PasswordResetOtpController::class, 'resetPassword'])
        ->name('password.otp.reset');

    // Resend OTP
    Route::post('/password-reset-otp/resend', [PasswordResetOtpController::class, 'resendOtp'])
        ->name('password.otp.resend');
});