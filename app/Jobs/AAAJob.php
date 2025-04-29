<?php

namespace App\Jobs;

use Exception;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Str;

abstract class AAAJob extends \HughCube\Laravel\Knight\Queue\Job
{
    /**
     * @var string
     */
    protected $logChannel = 'queue';

    /**
     * @param array<int|string, mixed> ...$arguments
     * @return PendingDispatch
     */
    public static function dispatchP(...$arguments): PendingDispatch
    {
        return static::dispatch(...$arguments)->onConnection('database');
    }

    /**
     * @param  int  $level
     * @param  string  $message
     * @param  array<int|string, mixed>  $context
     * @return void
     * @throws Exception
     */
    public function log($level, string $message, array $context = []): void
    {
        if (config('app.debug')) {
            $name = Str::afterLast(get_class($this), '\\');
            echo sprintf('[%s-%s] %s', $name, $this->getPid(), $message), PHP_EOL;
        }

        parent::log($level, $message, $context);
    }
}
