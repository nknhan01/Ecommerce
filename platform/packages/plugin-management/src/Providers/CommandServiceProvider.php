<?php

namespace Platform\PluginManagement\Providers;

use Platform\PluginManagement\Commands\PluginActivateAllCommand;
use Platform\PluginManagement\Commands\PluginActivateCommand;
use Platform\PluginManagement\Commands\PluginAssetsPublishCommand;
use Platform\PluginManagement\Commands\PluginDeactivateCommand;
use Platform\PluginManagement\Commands\PluginRemoveCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PluginAssetsPublishCommand::class,
            ]);
        }

        $this->commands([
            PluginActivateCommand::class,
            PluginDeactivateCommand::class,
            PluginRemoveCommand::class,
            PluginActivateAllCommand::class,
        ]);
    }
}
