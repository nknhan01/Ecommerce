<?php

namespace Platform\DemoHomework\Providers;

use Platform\DemoHomework\Models\DemoHomework;
use Illuminate\Support\ServiceProvider;
use Platform\DemoHomework\Repositories\Caches\DemoHomeworkCacheDecorator;
use Platform\DemoHomework\Repositories\Eloquent\DemoHomeworkRepository;
use Platform\DemoHomework\Repositories\Interfaces\DemoHomeworkInterface;
use Platform\Base\Supports\Helper;
use Event;
use Platform\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class DemoHomeworkServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(DemoHomeworkInterface::class, function () {
            return new DemoHomeworkCacheDecorator(new DemoHomeworkRepository(new DemoHomework));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/demo-homework')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes(['web']);

        Event::listen(RouteMatched::class, function () {
            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                \Language::registerModule([DemoHomework::class]);
            }

            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-demo-homework',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/demo-homework::demo-homework.name',
                'icon'        => 'fa fa-list',
                'url'         => route('demo-homework.index'),
                'permissions' => ['demo-homework.index'],
            ]);
        });
    }
}
