<?php

namespace App\Http\Api\Controllers;

use Symfony\Component\HttpFoundation\Response;

class HelloWorldAAAController extends AAAController
{
    /**
     * {@inheritDoc}
     */
    protected function action(): Response
    {
        return $this->asResponse(['message' => 'Hello World.']);
    }
}
