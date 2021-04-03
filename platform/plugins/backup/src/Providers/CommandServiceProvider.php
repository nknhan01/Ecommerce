<?php

namespace Platform\Backup\Providers;

use Platform\Backup\Commands\BackupCreateCommand;
use Platform\Backup\Commands\BackupListCommand;
use Platform\Backup\Commands\BackupRemoveCommand;
use Platform\Backup\Commands\BackupRestoreCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                BackupCreateCommand::class,
                BackupRestoreCommand::class,
                BackupRemoveCommand::class,
                BackupListCommand::class,
            ]);
        }
    }
}
