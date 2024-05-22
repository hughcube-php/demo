<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Exceptions\Handler as ExceptionHandler;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        /** global middleware */
        $middleware->use([
            #\HughCube\Profiler\Laravel\Middleware::class,
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);

        /** web middleware group */
        $middleware->group('web', [

        ]);

        /** api middleware group */
        $middleware->group('api', [

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withBindings([
        ExceptionHandlerContract::class => ExceptionHandler::class,
    ])
    ->create();
