<?php
/**
 * Created by PhpStorm.
 * User: hugh.li
 * Date: 2022/7/26
 * Time: 19:48
 */

namespace App\Http\Api\Middleware;

use Closure;
use Illuminate\Http\Request;

class Authenticate extends \HughCube\Laravel\Knight\Http\Middleware\Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        return parent::handle($request, $next, 'sanctum');
    }

    protected function isOptional(Request $request): bool
    {
        if (null != $request->headers->get('Authorization')) {
            return false;
        }

        return parent::isOptional($request);
    }

    /**
     * @return string[]
     */
    protected function getOptional(): array
    {
        return [
            /** api接口 */
            'api/login/*',
        ];
    }
}
