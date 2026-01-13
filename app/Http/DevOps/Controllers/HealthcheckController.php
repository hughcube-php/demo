<?php

namespace App\Http\DevOps\Controllers;

use Illuminate\Http\Response;

class HealthcheckController extends AAAController
{
    protected function action(): Response
    {
        return new Response('success');
    }
}
