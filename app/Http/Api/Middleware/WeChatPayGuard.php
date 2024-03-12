<?php
/**
 * Created by PhpStorm.
 * User: hugh.li
 * Date: 2022/7/26
 * Time: 20:02
 */

namespace App\Http\Api\Middleware;

use Closure;
use HughCube\Laravel\Knight\Exceptions\UserException;
use HughCube\Laravel\Knight\Exceptions\ValidateSignatureException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WeChatPayGuard
{
    protected function getVersion(): null|string
    {
        return '1.0.0';
    }

    /**
     *
     * @throws ValidateSignatureException
     * @throws UserException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            'mp-weixin' == strtolower($request->getClientPlatform())
            && 'ios' == strtolower($request->getClientOs())
            && !empty($this->getVersion())
            && $request->isLtClientVersion($this->getVersion(), true)
        ) {
            throw new UserException('受有关政策影响, 苹果手机下的微信不允许支付查询!');
        }

        return $next($request);
    }
}
