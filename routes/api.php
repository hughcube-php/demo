<?php

use App\Http\Api\Controllers;
use Illuminate\Support\Facades\Route;

Route::any('/hello-world', Controllers\HelloWorldController::class);
