<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'token.required' => 'Token reset password tidak valid.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Log password reset attempt
        \Log::info('Password reset attempted', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));

                // Log successful password reset
                \Log::info('Password reset successful', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip(),
                ]);
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's login screen. If there is an error we can redirect
        // them back to where they came from with their error message.
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Password Anda berhasil direset. Silakan login dengan password baru.');
        }

        // Handle different error cases
        $errorMessage = match ($status) {
            Password::INVALID_TOKEN => 'Token reset password tidak valid atau sudah kedaluwarsa.',
            Password::INVALID_USER => 'Email tidak ditemukan dalam sistem.',
            Password::RESET_THROTTLED => 'Terlalu banyak percobaan reset password. Silakan tunggu beberapa menit.',
            default => 'Terjadi kesalahan saat reset password. Silakan coba lagi.'
        };

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => $errorMessage]);
    }
}