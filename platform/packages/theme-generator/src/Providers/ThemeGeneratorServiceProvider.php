<?php

namespace Platform\ThemeGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use Platform\Base\Traits\LoadAndPublishDataTrait;

class ThemeGeneratorServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this->app->register(CommandServiceProvider::class);
    }
}
