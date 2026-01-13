<?php

namespace App\Providers;

use App\Mixin\Http\RequestMixin;
use HughCube\Laravel\Knight\Traits\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use ReflectionException;
use Tymon\JWTAuth\Factory as JWTAuthFactory;

class AppServiceProvider extends ServiceProvider
{
    use Container;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->resolving('tymon.jwt.payload.factory', function (JWTAuthFactory $factory, $app) {
            $factory->setDefaultClaims(['iat', 'exp', 'nbf', 'jti']);
        });
    }

    /**
     * Bootstrap any application services.
     * @throws ReflectionException
     */
    public function boot(): void
    {
        Request::mixin(new RequestMixin());

        if (str_starts_with($this->getContainerConfig()->get('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
