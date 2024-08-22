<?php

namespace App\Http\Api\Controllers;

use HughCube\GuzzleHttp\HttpClientTrait;
use Symfony\Component\HttpFoundation\Response;

class HelloWorldController extends AAAController
{
    use HttpClientTrait;

    /**
     * {@inheritDoc}
     */
    protected function action(): Response
    {
        $timeout = $this->getRequest()->get('timeout');
        if (!empty($timeout)) {
            usleep($timeout);
        }

        return $this->asResponse(['message' => 'Hello World.']);
    }
}
