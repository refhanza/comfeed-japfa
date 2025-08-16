<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        // Clean email input
        $email = trim(strtolower($request->email));

        // Rate limiting check
        $key = 'password-reset:' . $email;
        if (cache()->has($key)) {
            return back()->withErrors([
                'email' => 'Anda sudah mengirim permintaan reset password. Silakan tunggu beberapa menit sebelum mencoba lagi.'
            ]);
        }

        // Debug: Log input
        \Log::info('Password reset attempt', [
            'original_email' => $request->email,
            'cleaned_email' => $email,
            'ip' => $request->ip(),
        ]);

        // Check if user exists (try multiple ways)
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            // Try with original case
            $user = User::where('email', $request->email)->first();
        }
        
        if (!$user) {
            // Try case-insensitive search
            $user = User::whereRaw('LOWER(email) = ?', [$email])->first();
        }

        // Debug: Log search result
        \Log::info('User search result', [
            'input_email' => $request->email,
            'cleaned_email' => $email,
            'user_found' => $user ? true : false,
            'user_id' => $user ? $user->id : null,
            'database_email' => $user ? $user->email : null,
        ]);

        if (!$user) {
            // List all users for debugging
            $allUsers = User::all(['id', 'email']);
            \Log::warning('Password reset failed - user not found', [
                'input_email' => $request->email,
                'all_users' => $allUsers->pluck('email')->toArray(),
            ]);
            
            return back()->withErrors([
                'email' => 'Email tidak ditemukan dalam sistem kami. Pastikan email yang Anda masukkan benar.'
            ]);
        }

        // Log successful user found
        \Log::info('User found for password reset', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'input_email' => $request->email,
        ]);

        // Send reset link using the user's actual email from database
        $status = Password::sendResetLink([
            'email' => $user->email
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            // Set rate limiting - 2 minutes
            $expiresAt = now()->addMinutes(2);
            cache()->put($key, true, $expiresAt);
            
            \Log::info('Password reset link sent successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'mail_driver' => config('mail.default'),
            ]);
            
            // Different message based on mail driver
            if (config('mail.default') === 'log') {
                return back()->with('status', 'Link reset password telah dibuat dan disimpan di log. Karena menggunakan mail driver "log", silakan check file storage/logs/laravel.log untuk melihat email yang dikirim.');
            } else {
                return back()->with('status', 'Link reset password telah dikirim ke email Anda. Silakan periksa inbox dan folder spam.');
            }
        }

        // Handle error cases
        if ($status === Password::INVALID_USER) {
            $errorMessage = 'Terjadi kesalahan dengan user. Silakan coba lagi.';
        } elseif ($status === Password::RESET_THROTTLED) {
            $errorMessage = 'Terlalu banyak permintaan reset password. Silakan tunggu beberapa menit.';
        } else {
            $errorMessage = 'Terjadi kesalahan saat mengirim link reset password. Silakan coba lagi.';
        }

        \Log::error('Password reset link failed', [
            'status' => $status,
            'user_id' => $user->id,
            'email' => $user->email,
            'error' => $errorMessage,
        ]);

        return back()->withErrors(['email' => $errorMessage]);
    }
}