<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PasswordResetOtpController extends Controller
{
    /**
     * Show request OTP form
     */
    public function showRequestForm()
    {
        return view('auth.password-reset-otp.request');
    }

    /**
     * Send OTP to email
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak terdaftar dalam sistem'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = $request->email;

        // Check rate limiting (max 3 requests per 15 minutes)
        $recentOtps = DB::table('password_otps')
            ->where('email', $email)
            ->where('created_at', '>', now()->subMinutes(15))
            ->count();

        if ($recentOtps >= 3) {
            return back()->withErrors([
                'email' => 'Terlalu banyak permintaan OTP. Silakan tunggu 15 menit.'
            ])->withInput();
        }

        // Delete old OTPs for this email
        DB::table('password_otps')->where('email', $email)->delete();

        // Generate 6-digit OTP
        $otp = sprintf('%06d', mt_rand(100000, 999999));
        $expiresAt = now()->addMinutes(5);

        // Save OTP to database
        DB::table('password_otps')->insert([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Send OTP via email
        try {
            Mail::send('auth.password-reset-otp.email', [
                'otp' => $otp,
                'email' => $email,
                'expires_in' => 5
            ], function ($message) use ($email) {
                $message->to($email)
                        ->subject('Kode OTP Reset Password - ' . config('app.name'));
            });

            session(['otp_email' => $email]);

            return redirect()->route('password.otp.verify.form')
                           ->with('success', 'Kode OTP telah dikirim ke email Anda. Kode berlaku selama 5 menit.');

        } catch (\Exception $e) {
            // Delete OTP if email failed to send
            DB::table('password_otps')->where('email', $email)->delete();
            
            \Log::error('Failed to send OTP email', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors([
                'email' => 'Gagal mengirim email. Silakan coba lagi.'
            ])->withInput();
        }
    }

    /**
     * Show OTP verification form
     */
    public function showVerifyForm()
    {
        if (!session('otp_email')) {
            return redirect()->route('password.otp.request')
                           ->withErrors(['email' => 'Silakan request OTP terlebih dahulu.']);
        }

        return view('auth.password-reset-otp.verify', [
            'email' => session('otp_email')
        ]);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:6',
            'email' => 'required|email'
        ], [
            'otp.required' => 'Kode OTP wajib diisi',
            'otp.digits' => 'Kode OTP harus 6 digit',
            'email.required' => 'Email wajib diisi'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = $request->email;
        $otp = $request->otp;

        // Find valid OTP
        $otpRecord = DB::table('password_otps')
            ->where('email', $email)
            ->where('otp', $otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors([
                'otp' => 'Kode OTP tidak valid atau sudah kadaluarsa.'
            ])->withInput();
        }

        // OTP valid, set session and redirect to reset form
        session([
            'otp_verified' => true,
            'otp_email' => $email,
            'otp_id' => $otpRecord->id
        ]);

        return redirect()->route('password.otp.reset.form')
                       ->with('success', 'Kode OTP berhasil diverifikasi. Silakan masukkan password baru.');
    }

    /**
     * Show reset password form
     */
    public function showResetForm()
    {
        if (!session('otp_verified') || !session('otp_email')) {
            return redirect()->route('password.otp.request')
                           ->withErrors(['email' => 'Silakan verifikasi OTP terlebih dahulu.']);
        }

        return view('auth.password-reset-otp.reset', [
            'email' => session('otp_email')
        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        if (!session('otp_verified') || !session('otp_email')) {
            return redirect()->route('password.otp.request')
                           ->withErrors(['email' => 'Session expired. Silakan mulai ulang proses reset password.']);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ], [
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $email = session('otp_email');
        $otpId = session('otp_id');

        try {
            // Update user password
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                return back()->withErrors([
                    'email' => 'User tidak ditemukan.'
                ]);
            }

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // Delete used OTP
            DB::table('password_otps')->where('id', $otpId)->delete();

            // Clear session
            session()->forget(['otp_verified', 'otp_email', 'otp_id']);

            \Log::info('Password reset successful via OTP', [
                'user_id' => $user->id,
                'email' => $email
            ]);

            return redirect()->route('login')
                           ->with('success', 'Password berhasil diubah. Silakan login dengan password baru.');

        } catch (\Exception $e) {
            \Log::error('Failed to reset password', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors([
                'password' => 'Gagal mengubah password. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $email = session('otp_email') ?? $request->email;

        if (!$email) {
            return redirect()->route('password.otp.request')
                           ->withErrors(['email' => 'Email tidak ditemukan. Silakan mulai ulang.']);
        }

        // Simulate request to send OTP again
        $request->merge(['email' => $email]);
        return $this->sendOtp($request);
    }
}