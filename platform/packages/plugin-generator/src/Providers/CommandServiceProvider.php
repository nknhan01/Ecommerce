<?php

namespace Platform\PluginGenerator\Providers;

use Platform\PluginGenerator\Commands\PluginCreateCommand;
use Platform\PluginGenerator\Commands\PluginListCommand;
use Platform\PluginGenerator\Commands\PluginMakeCrudCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PluginListCommand::class,
                PluginCreateCommand::class,
                PluginMakeCrudCommand::class,
            ]);
        }
    }
}
