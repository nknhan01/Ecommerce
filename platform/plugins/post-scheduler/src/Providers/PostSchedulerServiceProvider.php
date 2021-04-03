<?php

namespace Platform\PostScheduler\Providers;

use Platform\Base\Traits\LoadAndPublishDataTrait;
use Platform\PostScheduler\Facades\PostSchedulerFacade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class PostSchedulerServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function boot()
    {
        if (is_plugin_active('blog')) {
            $this->setNamespace('plugins/post-scheduler')
                ->loadAndPublishConfigurations(['general'])
                ->loadAndPublishTranslations()
                ->loadAndPublishViews();

            AliasLoader::getInstance()->alias('PostScheduler', PostSchedulerFacade::class);

            $this->app->register(HookServiceProvider::class);
        }
    }
}
