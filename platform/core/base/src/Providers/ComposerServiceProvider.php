<?php

namespace Botble\Base\Providers;

use Assets;
use Illuminate\Support\Facades\Auth;
use Botble\ACL\Models\UserMeta;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;
use RvMedia;

class ComposerServiceProvider extends ServiceProvider
{

    /**
     * @param Factory $view
     */
    public function boot(Factory $view)
    {
        $view->composer(['core/base::layouts.partials.top-header'], function (View $view) {
            $themes = Assets::getThemes();
            $locales = Assets::getAdminLocales();

            if (Auth::check() && !session()->has('admin-theme')) {
                $activeTheme = UserMeta::getMeta('admin-theme', config('core.base.general.default-theme'));
            } elseif (session()->has('admin-theme')) {
                $activeTheme = session('admin-theme');
            } else {
                $activeTheme = config('core.base.general.default-theme');
            }

            if (!array_key_exists($activeTheme, $themes)) {
                $activeTheme = config('core.base.general.default-theme');
            }

            if (array_key_exists($activeTheme, $themes)) {
                Assets::addStylesDirectly($themes[$activeTheme]);
            }

            session(['admin-theme' => $activeTheme]);

            $view->with(compact('themes', 'locales', 'activeTheme'));
        });

        $view->composer(['core/acl::auth.master'], function (View $view) {
            $themes = Assets::getThemes();
            $activeTheme = config('core.base.general.default-theme');

            if (array_key_exists($activeTheme, $themes)) {
                Assets::addStylesDirectly($themes[$activeTheme]);
            }

            $view->with(compact('themes', 'activeTheme'));
        });

        $view->composer(['core/media::config'], function () {
            $mediaPermissions = config('core.media.media.permissions');
            if (Auth::check() && !Auth::user()->isSuperUser()) {
                $mediaPermissions = array_intersect(array_keys(Auth::user()->permissions),
                    config('core.media.media.permissions'));
            }
            RvMedia::setPermissions($mediaPermissions);
        });
    }
}
