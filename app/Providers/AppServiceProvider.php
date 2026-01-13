<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

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
        // Register route middleware alias for admin checks
        $this->app->make(Router::class)->aliasMiddleware('isAdmin', \App\Http\Middleware\IsAdmin::class);
    }
}
