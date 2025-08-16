<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" or default route.
     * 
     * Typically, users are redirected here after authentication.
     * Changed from '/home' to '/dashboard' for better UX.
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Enhanced rate limiting for authentication routes
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        // Rate limiting for password reset
        RateLimiter::for('password-reset', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
            
            // Authentication routes with rate limiting
            Route::middleware(['web', 'throttle:auth'])
                ->group(base_path('routes/auth.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // General API rate limiting
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        // Login attempts rate limiting
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            
            return [
                // Per email: 5 attempts per minute
                Limit::perMinute(5)->by($email.$request->ip())->response(function () {
                    return response()->json([
                        'message' => 'Terlalu banyak percobaan login. Silakan coba lagi setelah 1 menit.',
                        'retry_after' => 60
                    ], 429);
                }),
                
                // Per IP: 10 attempts per minute
                Limit::perMinute(10)->by($request->ip())->response(function () {
                    return response()->json([
                        'message' => 'Terlalu banyak percobaan login dari IP ini. Silakan coba lagi setelah 1 menit.',
                        'retry_after' => 60
                    ], 429);
                }),
            ];
        });

        // Registration rate limiting
        RateLimiter::for('register', function (Request $request) {
            return [
                // Per IP: 3 registrations per hour
                Limit::perHour(3)->by($request->ip())->response(function () {
                    return response()->json([
                        'message' => 'Terlalu banyak registrasi dari IP ini. Silakan coba lagi setelah 1 jam.',
                        'retry_after' => 3600
                    ], 429);
                }),
            ];
        });

        // Password reset rate limiting
        RateLimiter::for('password-reset', function (Request $request) {
            $email = (string) $request->email;
            
            return [
                // Per email: 3 requests per hour
                Limit::perHour(3)->by($email)->response(function () {
                    return response()->json([
                        'message' => 'Terlalu banyak permintaan reset password untuk email ini. Silakan coba lagi setelah 1 jam.',
                        'retry_after' => 3600
                    ], 429);
                }),
                
                // Per IP: 10 requests per hour
                Limit::perHour(10)->by($request->ip())->response(function () {
                    return response()->json([
                        'message' => 'Terlalu banyak permintaan reset password dari IP ini. Silakan coba lagi setelah 1 jam.',
                        'retry_after' => 3600
                    ], 429);
                }),
            ];
        });

        // Email verification rate limiting
        RateLimiter::for('email-verification', function (Request $request) {
            return [
                // Per user: 5 requests per hour
                Limit::perHour(5)->by($request->user()?->id ?: $request->ip())->response(function () {
                    return response()->json([
                        'message' => 'Terlalu banyak permintaan verifikasi email. Silakan coba lagi setelah 1 jam.',
                        'retry_after' => 3600
                    ], 429);
                }),
            ];
        });

        // General form submission rate limiting
        RateLimiter::for('forms', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        // File upload rate limiting
        RateLimiter::for('uploads', function (Request $request) {
            return [
                // Per user: 20 uploads per hour
                Limit::perHour(20)->by($request->user()?->id ?: $request->ip())->response(function () {
                    return response()->json([
                        'message' => 'Terlalu banyak upload file. Silakan coba lagi setelah 1 jam.',
                        'retry_after' => 3600
                    ], 429);
                }),
            ];
        });

        // Export/Download rate limiting
        RateLimiter::for('exports', function (Request $request) {
            return [
                // Per user: 10 exports per hour
                Limit::perHour(10)->by($request->user()?->id ?: $request->ip())->response(function () {
                    return response()->json([
                        'message' => 'Terlalu banyak permintaan export. Silakan coba lagi setelah 1 jam.',
                        'retry_after' => 3600
                    ], 429);
                }),
            ];
        });
    }
}