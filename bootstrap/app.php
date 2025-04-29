<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {

            Route::prefix('api')->middleware('api')->group(base_path('routes/api.php'));

            Route::any('/', \HughCube\Laravel\Knight\Http\Actions\PingAction::class);
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([

        ]);

        /** global middleware */
        $middleware->use([
            \HughCube\Laravel\Octane\Middleware\ClearTimeOutTimerGuard::class,
            \HughCube\Profiler\Laravel\Middleware::class,
            #\HughCube\Laravel\Knight\Http\Middleware\TrustProxies::class,
            #\HughCube\Laravel\Knight\Http\Middleware\SetHstsHeaderIfHttps::class,
            \HughCube\Laravel\Knight\Http\Middleware\HandleAllPathCors::class,
        ]);

        /** web middleware group */
        $middleware->group('web', [

        ]);

        /** api middleware group */
        $middleware->group('api', [
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions){

    })
    ->withSingletons([
        \Illuminate\Contracts\Debug\ExceptionHandler::class => \App\Exceptions\Handler::class
    ])
    ->create();
