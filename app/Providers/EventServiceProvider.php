<?php

namespace App\Providers;

use HughCube\Laravel\AliFC\Actions\PreStopAction;
use HughCube\Laravel\Knight\Database\Listeners\AssertCommittedTransaction;
use HughCube\Laravel\Knight\Events\ActionProcessed;
use HughCube\Laravel\Octane\Listeners\WaitTaskComplete as OctaneWaitTaskComplete;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        ActionProcessed::class => [
            AssertCommittedTransaction::class,
        ],
        PreStopAction::class => [
            OctaneWaitTaskComplete::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
