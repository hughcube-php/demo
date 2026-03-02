<?php

namespace App\Providers;

use App\Mixin\Http\RequestMixin;
use HughCube\Laravel\Knight\Traits\Container;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

        if (class_exists(\Laravel\Telescope\Telescope::class)) {
            /** 构建中不记录任何的信息 */
            if ('1' === getenv('APP_BUILDING')) {
                \Laravel\Telescope\Telescope::stopRecording();
            }

            /** 暗色主题 */
            \Laravel\Telescope\Telescope::night();

            /** 记录所有请求（默认非 local 环境只记录异常） */
            \Laravel\Telescope\Telescope::filter(fn() => true);

            /** 跳过 Gate 鉴权（已通过 IP 白名单限制访问） */
            \Laravel\Telescope\Telescope::auth(fn() => true);
        }
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

        /** 将 Telescope 请求条目的 UUID 写入响应头，方便排查问题 */
        if (class_exists(\Laravel\Telescope\Telescope::class)) {
            $this->app['events']->listen(RequestHandled::class, function (RequestHandled $event) {
                $entry = Collection::make(\Laravel\Telescope\Telescope::$entriesQueue)->first(fn($e) => $e->type === \Laravel\Telescope\EntryType::REQUEST);
                if ($entry) {
                    $event->response->headers->set('X-Telescope-Uuid', $entry->uuid);
                }
            });
        }
    }
}
