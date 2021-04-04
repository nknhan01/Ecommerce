<?php

namespace Platform\Razorpay\Providers;

use Platform\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\ServiceProvider;
use Platform\Base\Supports\Helper;

class RazorpayServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @throws FileNotFoundException
     */
    public function boot()
    {
        if (is_plugin_active('payment')) {
            $this->setNamespace('plugins/razorpay')
                ->loadAndPublishViews()
                ->publishAssets();

            $this->app->register(HookServiceProvider::class);
        }
    }
}
