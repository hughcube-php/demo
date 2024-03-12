<?php

namespace Tests\App\Jobs;

use HughCube\Laravel\Knight\OPcache\Jobs\WatchOpcacheScriptsJob;
use HughCube\Laravel\Knight\Queue\Job;
use HughCube\Laravel\Knight\Queue\Jobs\CleanFilesJob;
use HughCube\Laravel\Knight\Queue\Jobs\RotateFileJob;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class WatchOpcacheScriptsJobTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHandle()
    {
        $this->testJob(new WatchOpcacheScriptsJob());
    }
}
