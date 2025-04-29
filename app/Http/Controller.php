<?php

namespace App\Http;

use HughCube\Laravel\Knight\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @method Request getRequest()
 */
abstract class Controller extends BaseController
{
    protected function asResponse(array $data = [], int $code = 200): JsonResponse
    {
        return new JsonResponse($data)->setStatusCode($code);
    }
}
