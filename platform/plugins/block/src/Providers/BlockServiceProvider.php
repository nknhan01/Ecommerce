<?php

namespace Platform\Block\Providers;

use Platform\Base\Traits\LoadAndPublishDataTrait;
use Platform\Block\Models\Block;
use Event;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use Platform\Block\Repositories\Caches\BlockCacheDecorator;
use Platform\Block\Repositories\Eloquent\BlockRepository;
use Platform\Block\Repositories\Interfaces\BlockInterface;
use Platform\Base\Supports\Helper;
use Language;

class BlockServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(BlockInterface::class, function () {
            return new BlockCacheDecorator(new BlockRepository(new Block));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/block')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->loadAndPublishViews()
            ->loadMigrations();

        $this->app->register(HookServiceProvider::class);

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-block',
                'priority'    => 6,
                'parent_id'   => null,
                'name'        => 'plugins/block::block.menu',
                'icon'        => 'fa fa-code',
                'url'         => route('block.index'),
                'permissions' => ['block.index'],
            ]);
        });

        $this->app->booted(function () {
            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                Language::registerModule([Block::class]);
            }

            if (defined('CUSTOM_FIELD_MODULE_SCREEN_NAME')) {
                \CustomField::registerModule(Block::class)
                    ->registerRule('basic', trans('plugins/block::block.name'), Block::class, function () {
                        return $this->app->make(BlockInterface::class)->pluck('blocks.name', 'blocks.id');
                    })
                    ->expandRule('other', trans('plugins/custom-field::rules.model_name'), 'model_name', function () {
                        return [
                            Block::class => trans('plugins/block::block.name'),
                        ];
                    });
            }
        });
    }
}
