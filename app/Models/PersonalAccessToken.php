<?php

namespace App\Models;

use HughCube\Laravel\Knight\Sanctum\PersonalAccessToken as BasePersonalAccessToken;

class PersonalAccessToken extends BasePersonalAccessToken
{
    use AAATrait;

    public static function getExpiresIn(): int
    {
        return 365 * 24 * 3600;
    }
}
