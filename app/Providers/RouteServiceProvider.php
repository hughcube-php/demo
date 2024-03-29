<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot(): void
    {
        //$this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')->middleware('api')->group(base_path('routes/api.php'));
        });
    }
}
