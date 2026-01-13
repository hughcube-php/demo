<?php
/**
 * Created by PhpStorm.
 * User: hugh.li
 * Date: 2022/6/30
 * Time: 16:07.
 */

namespace App\Mixin\Http;

use Closure;
use Illuminate\Http\Request;

/**
 * @mixin Request
 */
class RequestMixin extends \HughCube\Laravel\Knight\Mixin\Http\RequestMixin
{
    public function getClientHeaderPrefix(): Closure
    {
        return function (): string {
            return 'X-Domo-';
        };
    }
}
