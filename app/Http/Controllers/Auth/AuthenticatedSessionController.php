<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Log successful login
        \Log::info('User logged in successfully', [
            'user_id' => Auth::id(),
            'email' => Auth::user()->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Get welcome message
        $user = Auth::user();
        $hour = now()->hour;
        $timeGreeting = '';
        
        if ($hour < 12) {
            $timeGreeting = 'Selamat pagi';
        } elseif ($hour < 17) {
            $timeGreeting = 'Selamat siang';
        } elseif ($hour < 21) {
            $timeGreeting = 'Selamat sore';
        } else {
            $timeGreeting = 'Selamat malam';
        }

        $welcomeMessage = "{$timeGreeting}, {$user->name}! Selamat datang kembali.";

        return redirect()->intended(RouteServiceProvider::HOME)
            ->with('success', $welcomeMessage);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log logout
        if (Auth::check()) {
            \Log::info('User logged out', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'ip' => $request->ip(),
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout. Sampai jumpa!');
    }
}