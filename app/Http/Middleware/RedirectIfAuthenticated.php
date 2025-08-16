<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Get the authenticated user
                $user = Auth::guard($guard)->user();
                
                // Log the redirect attempt for security monitoring
                \Log::info('Authenticated user attempted to access guest route', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'requested_url' => $request->fullUrl(),
                    'timestamp' => now()
                ]);

                // Determine redirect URL based on user role or preferences
                $redirectUrl = $this->getRedirectUrl($user, $request);
                
                // Add success message for better UX
                $message = $this->getWelcomeMessage($user);
                
                return redirect($redirectUrl)->with('success', $message);
            }
        }

        return $next($request);
    }

    /**
     * Get the appropriate redirect URL for the authenticated user.
     */
    private function getRedirectUrl($user, Request $request): string
    {
        // Check if there's an intended URL in the session
        if (session()->has('url.intended')) {
            $intended = session()->pull('url.intended');
            
            // Validate that the intended URL is safe and belongs to our application
            if ($this->isSafeRedirectUrl($intended, $request)) {
                return $intended;
            }
        }

        // Check if user has a specific role-based redirect
        if (method_exists($user, 'getHomeRoute')) {
            return route($user->getHomeRoute());
        }

        // Check user role for different redirects (if you have role-based system)
        if (isset($user->role)) {
            switch ($user->role) {
                case 'admin':
                    return route('dashboard'); // or admin-specific dashboard
                case 'manager':
                    return route('dashboard');
                case 'staff':
                    return route('dashboard');
                default:
                    return RouteServiceProvider::HOME;
            }
        }

        // Default redirect to dashboard
        return RouteServiceProvider::HOME;
    }

    /**
     * Get a personalized welcome message for the user.
     */
    private function getWelcomeMessage($user): string
    {
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

        $name = $user->name ?? 'User';
        
        return "{$timeGreeting}, {$name}! Anda sudah login.";
    }

    /**
     * Check if the redirect URL is safe to prevent open redirect vulnerabilities.
     */
    private function isSafeRedirectUrl(string $url, Request $request): bool
    {
        // Parse the URL
        $parsedUrl = parse_url($url);
        
        // If it's a relative URL, it's safe
        if (!isset($parsedUrl['host'])) {
            return true;
        }
        
        // Check if the host matches our application
        $appHost = parse_url(config('app.url'), PHP_URL_HOST);
        $requestHost = $request->getHost();
        
        return in_array($parsedUrl['host'], [$appHost, $requestHost]);
    }

    /**
     * Handle specific guest routes that should redirect authenticated users.
     */
    public function handleGuestRoutes(Request $request): ?string
    {
        $route = $request->route();
        
        if (!$route) {
            return null;
        }
        
        $routeName = $route->getName();
        
        // Specific handling for different auth routes
        switch ($routeName) {
            case 'login':
                return RouteServiceProvider::HOME;
                
            case 'register':
                return RouteServiceProvider::HOME;
                
            case 'password.request':
            case 'password.reset':
                return RouteServiceProvider::HOME;
                
            case 'verification.notice':
                // If user is already verified, redirect to dashboard
                if (Auth::user() && Auth::user()->hasVerifiedEmail()) {
                    return RouteServiceProvider::HOME;
                }
                break;
                
            default:
                return RouteServiceProvider::HOME;
        }
        
        return null;
    }
}