<?php

namespace App\Jobs;

use HughCube\Laravel\Knight\OPcache\Jobs\WatchOpcacheScriptsJob;
use HughCube\Laravel\Knight\Queue\Job;
use HughCube\Laravel\Knight\Queue\Jobs\CleanFilesJob;
use HughCube\Laravel\Knight\Queue\Jobs\RotateFileJob;
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

    protected function rotateFileJobHandler(): void
    {
        $this->fireJobIfDue('00 00 * * *', function () {
            return RotateFileJob::new([
                'items' => [
                    [
                        'pattern' => [
                            'php-error.log',
                            'php-opcache-error.log',
                        ],

                        'dir' => Collection::empty()
                            ->add(log_path())->add(storage_path('logs'))
                            ->unique()->values()->toArray(),

                        'date_format' => Carbon::now()->subHours(12)->format('Y-m-d'),
                    ],
                ],
            ]);
        });
    }

    protected function cleanFilesJobHandler(): void
    {
        $this->fireJobIfDue('01 00 * * *', function () {
            return CleanFilesJob::new([
                'items' => [
                    [
                        'max_days' => 30,
                        'pattern' => [
                            '/^apache-.*-\d{4}-\d{2}-\d{2}\.log$/',
                            '/^laravel-.*-\d{4}-\d{2}-\d{2}\.log$/',
                            '/^php-.*-\d{4}-\d{2}-\d{2}\.log$/',
                        ],
                        'dir' => Collection::empty()
                            ->add(log_path())->add(storage_path('logs'))
                            ->unique()->values()->toArray(),
                    ],
                ],
            ]);
        });
    }
}
