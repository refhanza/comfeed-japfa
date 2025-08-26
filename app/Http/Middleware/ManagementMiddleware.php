<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagementMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $allowedRoles = ['admin', 'manager', 'staff'];

        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses untuk halaman ini. Diperlukan role: ' . implode(', ', $allowedRoles));
        }

        return $next($request);
    }
}