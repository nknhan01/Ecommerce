<?php

namespace Platform\GitCommitChecker\Providers;

use Platform\GitCommitChecker\Commands\InstallHooks;
use Platform\GitCommitChecker\Commands\InstallPhpCs;
use Platform\GitCommitChecker\Commands\PreCommitHook;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallHooks::class,
                PreCommitHook::class,
                InstallPhpCs::class,
            ]);
        }
    }
}
