<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('register', fn($request) => Limit::perMinute(3)->by($request->ip()));
        RateLimiter::for('login', fn($request)    => Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('logout', fn($request)   => Limit::perMinute(3)->by($request->ip()));
        RateLimiter::for('me', fn($request)       => Limit::perMinute(100)->by($request->user()?->id ?: $request->ip()));

        RateLimiter::for('orders', fn($request) => Limit::perMinute(100)->by($request->user()?->id ?: $request->ip()));
    }
}
