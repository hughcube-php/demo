<?php

namespace App\Exceptions;

use HughCube\Laravel\Knight\Exceptions\Contracts\DataExceptionInterface;
use HughCube\Laravel\Knight\Exceptions\Contracts\ResponseExceptionInterface;
use HughCube\Laravel\Knight\Exceptions\UserException;
use HughCube\Laravel\Knight\Exceptions\ValidatePinCodeException;
use HughCube\Laravel\Knight\Exceptions\ValidateSignatureException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends \HughCube\Laravel\Knight\Exceptions\Handler
{
    /**
     * @inheritdoc
     */
    protected $dontReport = [
        HttpException::class,
        UserException::class,

        DataExceptionInterface::class,
        ResponseExceptionInterface::class,

        HttpException::class,
        ValidateSignatureException::class,
        ValidatePinCodeException::class,
        AuthenticationException::class,
        ValidationException::class,
    ];
}
