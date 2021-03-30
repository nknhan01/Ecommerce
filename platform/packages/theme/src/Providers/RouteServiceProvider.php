<?php

namespace Botble\Theme\Providers;

use File;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Move base routes to a service provider to make sure all filters & actions can hook to base routes
     */
    public function boot()
    {
        $this->app->booted(function () {

            $themeRoute = theme_path(setting('theme') . '/routes/web.php');
            if (File::exists($themeRoute)) {
                $this->loadRoutesFrom($themeRoute);
            }

            $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        });
    }
}
