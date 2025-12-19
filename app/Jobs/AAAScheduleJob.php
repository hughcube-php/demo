<?php

namespace App\Jobs;

use HughCube\Laravel\Knight\OPcache\Jobs\WatchOpcacheScriptsJob;
use HughCube\Laravel\Knight\Queue\Job;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AAAScheduleJob extends \HughCube\Laravel\Knight\Queue\Jobs\ScheduleJob
{
    /**
     * @var string
     */
    protected $logChannel = 'schedule';

    /**
     * @param  Job  $job
     */
    protected function prepareJob($job): Job
    {
        if (empty($job->getLogChannel())) {
            $job->setLogChannel('queue');
        }

        return $job;
    }
}
