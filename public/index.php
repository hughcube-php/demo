<?php

use App\Http\Request;
use HughCube\Profiler\HProfiler;
use HughCube\PUrl\Url as PUrl;
use Illuminate\Contracts\Http\Kernel;

/** Enable Profiler */
if (define('PROFILER_ENABLE', rand(0, 100) < 5) && PROFILER_ENABLE) {
    xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
}

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

//if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
//    require $maintenance;
//}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/** @var \App\Http\Kernel $kernel */
$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::createFromGlobals()
)->send();

$kernel->terminate($request, $response);

/** Save Profiler Data */
if (PROFILER_ENABLE) {
    HProfiler::save(
        sprintf('%.6F', LARAVEL_START),

        xhprof_disable(),

        ($request->getPathInfo() ?: '/'),

        $request->query->all(),

        array_merge($request->server->all(), array_filter([
            'SERVER_NAME' => call_user_func(function (Request $request): ?string {
                $url = PUrl::parse($request->fullUrl());
                if ($url instanceof PUrl && !$url->isIp() && !$url->isLocalhost()) {
                    return $url->getHost();
                }

                $appUrl = PUrl::parse(config('app.url'));
                if ($appUrl instanceof PUrl && !$appUrl->isLocalhost()) {
                    return $appUrl->getHost();
                }

                return null;
            }, $request)
        ]))
    )->await();
}
