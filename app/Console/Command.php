<?php

/**
 * Created by PhpStorm.
 * User: hugh.li
 * Date: 2021/2/22
 * Time: 11:18
 */

namespace App\Console;

use HughCube\Laravel\Knight\Console\Command as KnightCommand;
use HughCube\Laravel\Knight\Traits\Container;

abstract class Command extends KnightCommand
{
    use Container;
}
