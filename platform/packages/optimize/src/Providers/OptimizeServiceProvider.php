<?php

namespace Platform\Optimize\Providers;

use Platform\Base\Traits\LoadAndPublishDataTrait;
use Platform\Optimize\Http\Middleware\CollapseWhitespace;
use Platform\Optimize\Http\Middleware\ElideAttributes;
use Platform\Optimize\Http\Middleware\InlineCss;
use Platform\Optimize\Http\Middleware\InsertDNSPrefetch;
use Platform\Optimize\Http\Middleware\RemoveComments;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class OptimizeServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this->setNamespace('packages/optimize')
            ->loadAndPublishConfigurations(['general'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews();

        $this->app->register(HookServiceProvider::class);

        /**
         * @var Router $router
         */
        $router = $this->app['router'];

        $router->pushMiddlewareToGroup('web', ElideAttributes::class);
        $router->pushMiddlewareToGroup('web', InsertDNSPrefetch::class);
        if (!$this->app->environment('local') && !is_in_admin()) {
            $router->pushMiddlewareToGroup('web', CollapseWhitespace::class);
        }
        $router->pushMiddlewareToGroup('web', RemoveComments::class);
        $router->pushMiddlewareToGroup('web', InlineCss::class);
    }
}
