<?php

xhprof_enable();

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
#if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
#    require $maintenance;
#}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel
/**
 * @var Application $app
 */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Handle the request...
$app->handleRequest($request = Request::capture());

\HughCube\Profiler\HProfiler::save(
    sprintf('%.6F', microtime(true)),
    xhprof_disable(),
    ($request->getPathInfo() ?: '/'),
    $request->query->all(),
    array_merge(
        $request->server->all(),
        array_filter(['SERVER_NAME' => $request->getHost()])
    )
);
