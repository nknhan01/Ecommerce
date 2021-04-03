<?php

namespace Platform\Base\Providers;

use Platform\Base\Commands\ClearLogCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            ClearLogCommand::class,
        ]);
    }
}
