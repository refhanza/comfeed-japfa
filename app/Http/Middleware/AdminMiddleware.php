<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            \Log::warning('Admin access denied', [
                'user_id' => auth()->id(),
                'user_role' => auth()->user()->role ?? 'guest',
                'url' => $request->url(),
                'ip' => $request->ip(),
            ]);

            return redirect()->route('dashboard')->with('error', 'Akses khusus admin diperlukan.');
        }

        return $next($request);
    }
}