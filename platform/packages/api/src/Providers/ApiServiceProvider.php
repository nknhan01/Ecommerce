<?php

namespace Platform\Api\Providers;

use Platform\Api\Http\Middleware\ForceJsonResponseMiddleware;
use Illuminate\Support\ServiceProvider;
use Platform\Base\Traits\LoadAndPublishDataTrait;

class ApiServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        $this->app->make('router')->pushMiddlewareToGroup('api', ForceJsonResponseMiddleware::class);
    }

    public function boot()
    {
        $this->setNamespace('packages/api')
            ->publishAssets();

        $this->app->booted(function () {
            config([
                'scribe.routes.0.match.prefixes' => ['api/*'],
                'scribe.routes.0.apply.headers'  => [
                    'Authorization' => 'Bearer {token}',
                    'Api-Version'   => 'v1',
                ],
            ]);
        });
    }
}
