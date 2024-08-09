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
        return $this->asResponse(['message' => 'Hello World.']);
    }
}
