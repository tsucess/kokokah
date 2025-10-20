<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // Routes are now configured in bootstrap/app.php
        // This method is kept for backward compatibility but does nothing
        // to avoid double-prefixing of routes
    }
}
