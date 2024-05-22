<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootSanctum();
    }

    protected function bootSanctum(): void
    {
        if (!class_exists(Sanctum::class)) {
            return;
        }

        /** 定义model类 */
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        /** 验证token有效 */
        Sanctum::$accessTokenAuthenticationCallback = function (PersonalAccessToken $accessToken, bool $isValid) {
            return $accessToken->isValidAccessToken($isValid);
        };
    }
}
