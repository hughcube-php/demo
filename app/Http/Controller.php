<?php

namespace App\Http;

use HughCube\Laravel\Knight\Routing\Controller as BaseController;
use Illuminate\Http\Request;

/**
 * @method Request getRequest()
 */
abstract class Controller extends BaseController
{
}
