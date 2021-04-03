<?php

namespace Platform\DevTool\Providers;

use Platform\DevTool\Commands\InstallCommand;
use Platform\DevTool\Commands\LocaleCreateCommand;
use Platform\DevTool\Commands\LocaleRemoveCommand;
use Platform\DevTool\Commands\Make\ControllerMakeCommand;
use Platform\DevTool\Commands\Make\FormMakeCommand;
use Platform\DevTool\Commands\Make\ModelMakeCommand;
use Platform\DevTool\Commands\Make\RepositoryMakeCommand;
use Platform\DevTool\Commands\Make\RequestMakeCommand;
use Platform\DevTool\Commands\Make\RouteMakeCommand;
use Platform\DevTool\Commands\Make\TableMakeCommand;
use Platform\DevTool\Commands\PackageCreateCommand;
use Platform\DevTool\Commands\PackageRemoveCommand;
use Platform\DevTool\Commands\RebuildPermissionsCommand;
use Platform\DevTool\Commands\TestSendMailCommand;
use Platform\DevTool\Commands\TruncateTablesCommand;
use Platform\DevTool\Commands\PackageMakeCrudCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TableMakeCommand::class,
                ControllerMakeCommand::class,
                RouteMakeCommand::class,
                RequestMakeCommand::class,
                FormMakeCommand::class,
                ModelMakeCommand::class,
                RepositoryMakeCommand::class,
                PackageCreateCommand::class,
                PackageMakeCrudCommand::class,
                PackageRemoveCommand::class,
                InstallCommand::class,
                TestSendMailCommand::class,
                TruncateTablesCommand::class,
                RebuildPermissionsCommand::class,
                LocaleRemoveCommand::class,
                LocaleCreateCommand::class,
            ]);
        }
    }
}
