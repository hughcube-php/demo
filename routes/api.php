<?php

use App\Http\Api\Controllers\HelloWorldController;
use Illuminate\Support\Facades\Route;


Route::get('/hello-world', HelloWorldController::class);
