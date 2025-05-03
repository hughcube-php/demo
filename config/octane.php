<?php

use Illuminate\Support\Collection;

return [

    /*
    |--------------------------------------------------------------------------
    | Octane Server
    |--------------------------------------------------------------------------
    |
    | This value determines the default "server" that will be used by Octane
    | when starting, restarting, or stopping your server via the CLI. You
    | are free to change this to the supported server of your choosing.
    |
    | Supported: "roadrunner", "swoole", "frankenphp"
    |
    */

    'server' => env('OCTANE_SERVER', 'swoole'),

    /*
    |--------------------------------------------------------------------------
    | Force HTTPS
    |--------------------------------------------------------------------------
    |
    | When this configuration value is set to "true", Octane will inform the
    | framework that all absolute links must be generated using the HTTPS
    | protocol. Otherwise your links may be generated using plain HTTP.
    |
    */

    'https' => env('OCTANE_HTTPS', false),

    /*
    |--------------------------------------------------------------------------
    | Octane Listeners
    |--------------------------------------------------------------------------
    |
    | All of the event listeners for Octane's events are defined below. These
    | listeners are responsible for resetting your application's state for
    | the next request. You may even add your own listeners to the list.
    |
    */

    'listeners' => [
        \Laravel\Octane\Events\WorkerStarting::class => [
            \Laravel\Octane\Listeners\EnsureUploadedFilesAreValid::class,
            \Laravel\Octane\Listeners\EnsureUploadedFilesCanBeMoved::class,
        ],

        \Laravel\Octane\Events\RequestReceived::class => Collection::make([
            #\HughCube\Laravel\Octane\Listeners\PrepareServerVariables::class,
            ...\Laravel\Octane\Octane::prepareApplicationForNextOperation(),
            ...\Laravel\Octane\Octane::prepareApplicationForNextRequest(),
        ])->diff(Collection::make([
            \Laravel\Octane\Listeners\FlushArrayCache::class,
            \Laravel\Octane\Listeners\FlushLocaleState::class,
            \Laravel\Octane\Listeners\FlushSessionState::class,
            \Laravel\Octane\Listeners\FlushQueuedCookies::class,
            \Laravel\Octane\Listeners\EnforceRequestScheme::class,
            \Laravel\Octane\Listeners\CreateUrlGeneratorSandbox::class,
            \Laravel\Octane\Listeners\CreateConfigurationSandbox::class,
            \Laravel\Octane\Listeners\GiveNewRequestInstanceToPaginator::class,
            \Laravel\Octane\Listeners\EnsureRequestServerPortMatchesScheme::class,
            \Laravel\Octane\Listeners\GiveNewApplicationInstanceToMailManager::class,
            \Laravel\Octane\Listeners\GiveNewApplicationInstanceToSessionManager::class,
            \Laravel\Octane\Listeners\GiveNewApplicationInstanceToBroadcastManager::class,
            \Laravel\Octane\Listeners\GiveNewApplicationInstanceToDatabaseSessionHandler::class,
            \Laravel\Octane\Listeners\GiveNewApplicationInstanceToNotificationChannelManager::class,
            !class_exists(\Inertia\ResponseFactory::class) ? \Laravel\Octane\Listeners\PrepareInertiaForNextOperation::class : null,
            !class_exists(\Laravel\Scout\EngineManager::class) ? \Laravel\Octane\Listeners\PrepareScoutForNextOperation::class : null,
            !class_exists(\Livewire\LivewireManager::class) ? \Laravel\Octane\Listeners\PrepareLivewireForNextOperation::class : null,
            !class_exists(\Laravel\Socialite\Contracts\Factory::class) ? \Laravel\Octane\Listeners\PrepareSocialiteForNextOperation::class : null,
        ])->filter()->values())->values()->toArray(),

        \Laravel\Octane\Events\RequestHandled::class => [
            //
        ],

        \Laravel\Octane\Events\RequestTerminated::class => [
            \Laravel\Octane\Listeners\FlushUploadedFiles::class,
        ],

        \Laravel\Octane\Events\TaskReceived::class => [
            ...\Laravel\Octane\Octane::prepareApplicationForNextOperation(),
            //
        ],

        \Laravel\Octane\Events\TaskTerminated::class => [
            //
        ],

        \Laravel\Octane\Events\TickReceived::class => [
            ...\Laravel\Octane\Octane::prepareApplicationForNextOperation(),
            //
        ],

        \Laravel\Octane\Events\TickTerminated::class => [
            //
        ],

        \Laravel\Octane\Contracts\OperationTerminated::class => [
            \Laravel\Octane\Listeners\FlushOnce::class,
            \Laravel\Octane\Listeners\FlushTemporaryContainerInstances::class,
            #\Laravel\Octane\Listeners\DisconnectFromDatabases::class,
            #\Laravel\Octane\Listeners\CollectGarbage::class,
        ],

        \Laravel\Octane\Events\WorkerErrorOccurred::class => [
            \Laravel\Octane\Listeners\ReportException::class,
            \Laravel\Octane\Listeners\StopWorkerIfNecessary::class,
        ],

        \Laravel\Octane\Events\WorkerStopping::class => [
            \Laravel\Octane\Listeners\CloseMonologHandlers::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Warm / Flush Bindings
    |--------------------------------------------------------------------------
    |
    | The bindings listed below will either be pre-warmed when a worker boots
    | or they will be flushed before every new request. Flushing a binding
    | will force the container to resolve that binding again when asked.
    |
    */

    'warm' => [
        ...\Laravel\Octane\Octane::defaultServicesToWarm(),
    ],

    'flush' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Octane Swoole Tables
    |--------------------------------------------------------------------------
    |
    | While using Swoole, you may define additional tables as required by the
    | application. These tables can be used to store data that needs to be
    | quickly accessed by other workers on the particular Swoole server.
    |
    */

    'tables' => [
    ],

    /*
    |--------------------------------------------------------------------------
    | Octane Swoole Cache Table
    |--------------------------------------------------------------------------
    |
    | While using Swoole, you may leverage the Octane cache, which is powered
    | by a Swoole table. You may set the maximum number of rows as well as
    | the number of bytes per row using the configuration options below.
    |
    */

    'cache' => [
        'rows' => 1000,
        'bytes' => 10000,
    ],

    /*
    |--------------------------------------------------------------------------
    | File Watching
    |--------------------------------------------------------------------------
    |
    | The following list of files and directories will be watched when using
    | the --watch option offered by Octane. If any of the directories and
    | files are changed, Octane will automatically reload your workers.
    |
    */

    'watch' => [
        'app',
        'bootstrap',
        'config/**/*.php',
        'database/**/*.php',
        'public/**/*.php',
        'resources/**/*.php',
        'routes',
        'composer.lock',
        '.env',
    ],

    /*
    |--------------------------------------------------------------------------
    | Garbage Collection Threshold
    |--------------------------------------------------------------------------
    |
    | When executing long-lived PHP scripts such as Octane, memory can build
    | up before being cleared by PHP. You can force Octane to run garbage
    | collection if your application consumes this amount of megabytes.
    |
    */

    'garbage' => 50,

    /*
    |--------------------------------------------------------------------------
    | Maximum Execution Time
    |--------------------------------------------------------------------------
    |
    | The following setting configures the maximum execution time for requests
    | being handled by Octane. You may set this value to 0 to indicate that
    | there isn't a specific time limit on Octane request execution time.
    |
    */

    'max_execution_time' => intval(env('OCTANE_MAX_EXECUTION_TIME', 30)),

    'tick' => false,

    'swoole' => [
        #'mode' => SWOOLE_BASE,
        'options' => [
            'dispatch_mode' => 3,
            'send_yield' => false,
            'max_wait_time' => 600,
            'http_compression' => false,

            'log_level' => 0, #SWOOLE_LOG_DEBUG,
            'log_rotation' => 2, # SWOOLE_LOG_ROTATION_DAILY,
            'log_file' => storage_path('logs/swoole_http.log'),
        ],
    ],

];
