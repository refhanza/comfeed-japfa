<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOrManagerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAdminOrManager()) {
            \Log::warning('Admin/Manager access denied', [
                'user_id' => auth()->id(),
                'user_role' => auth()->user()->role ?? 'guest',
                'url' => $request->url(),
                'ip' => $request->ip(),
            ]);

            return redirect()->route('dashboard')->with('error', 'Akses admin atau manager diperlukan.');
        }

        return $next($request);
    }
}