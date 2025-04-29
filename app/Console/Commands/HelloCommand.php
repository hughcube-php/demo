<?php

/**
 * Created by PhpStorm.
 * User: hugh.li
 * Date: 2021/2/22
 * Time: 11:18
 */

namespace App\Console\Commands;

use AllowDynamicProperties;
use App\Console\Command;
use Exception;
use Illuminate\Console\Scheduling\Schedule;

class HelloCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected $signature = 'hello';

    /**
     * @inheritdoc
     */
    protected $description = 'Hello!';

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(Schedule $schedule): void
    {
        $this->info('Hello world!');
    }
}
