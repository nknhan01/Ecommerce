<?php

namespace Platform\Impersonate\Providers;

use Platform\Impersonate\Models\User;
use Illuminate\Support\ServiceProvider;
use Platform\Base\Traits\LoadAndPublishDataTrait;

class ImpersonateServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this->setNamespace('plugins/impersonate')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web']);

        config()->set(['auth.providers.users.model' => User::class]);

        $this->app->register(HookServiceProvider::class);
    }
}
