<?php

namespace Platform\Menu\Providers;

use Platform\Base\Supports\Helper;
use Platform\Base\Traits\LoadAndPublishDataTrait;
use Platform\Menu\Models\Menu as MenuModel;
use Platform\Menu\Models\MenuLocation;
use Platform\Menu\Models\MenuNode;
use Platform\Menu\Repositories\Caches\MenuCacheDecorator;
use Platform\Menu\Repositories\Caches\MenuLocationCacheDecorator;
use Platform\Menu\Repositories\Caches\MenuNodeCacheDecorator;
use Platform\Menu\Repositories\Eloquent\MenuLocationRepository;
use Platform\Menu\Repositories\Eloquent\MenuNodeRepository;
use Platform\Menu\Repositories\Eloquent\MenuRepository;
use Platform\Menu\Repositories\Interfaces\MenuInterface;
use Platform\Menu\Repositories\Interfaces\MenuLocationInterface;
use Platform\Menu\Repositories\Interfaces\MenuNodeInterface;
use Event;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->app->bind(MenuInterface::class, function () {
            return new MenuCacheDecorator(
                new MenuRepository(new MenuModel)
            );
        });

        $this->app->bind(MenuNodeInterface::class, function () {
            return new MenuNodeCacheDecorator(
                new MenuNodeRepository(new MenuNode)
            );
        });

        $this->app->bind(MenuLocationInterface::class, function () {
            return new MenuLocationCacheDecorator(
                new MenuLocationRepository(new MenuLocation)
            );
        });

        $this->setNamespace('packages/menu')
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadRoutes(['web'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-core-menu',
                    'priority'    => 2,
                    'parent_id'   => 'cms-core-appearance',
                    'name'        => 'packages/menu::menu.name',
                    'icon'        => null,
                    'url'         => route('menus.index'),
                    'permissions' => ['menus.index'],
                ]);

            if (!defined('THEME_MODULE_SCREEN_NAME')) {
                dashboard_menu()
                    ->registerItem([
                        'id'          => 'cms-core-appearance',
                        'priority'    => 996,
                        'parent_id'   => null,
                        'name'        => 'packages/theme::theme.appearance',
                        'icon'        => 'fa fa-paint-brush',
                        'url'         => '#',
                        'permissions' => [],
                    ]);
            }

            if (function_exists('admin_bar') && Auth::check() && Auth::user()->hasPermission('menus.index')) {
                admin_bar()->registerLink(trans('packages/menu::menu.name'), route('menus.index'), 'appearance');
            }
        });

        $this->app->register(EventServiceProvider::class);
        $this->app->register(CommandServiceProvider::class);
    }
}
