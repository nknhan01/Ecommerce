<?php

namespace Platform\SocialLogin\Providers;

use Platform\Setting\Supports\SettingStore;
use Platform\SocialLogin\Facades\SocialServiceFacade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Platform\Base\Supports\Helper;
use Illuminate\Routing\Events\RouteMatched;
use Event;
use Platform\Base\Traits\LoadAndPublishDataTrait;

class SocialLoginServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/social-login')
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadMigrations()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-social-login',
                'priority'    => 5,
                'parent_id'   => 'cms-core-settings',
                'name'        => 'plugins/social-login::social-login.menu',
                'icon'        => null,
                'url'         => route('social-login.settings'),
                'permissions' => ['social-login.settings'],
            ]);
        });

        AliasLoader::getInstance()->alias('SocialService', SocialServiceFacade::class);

        $this->app->booted(function () {
            $config = $this->app->make('config');
            $setting = $this->app->make(SettingStore::class);

            $config->set([
                'services.facebook' => [
                    'client_id'     => $setting->get('social_login_facebook_app_id'),
                    'client_secret' => $setting->get('social_login_facebook_app_secret'),
                    'redirect'      => route('auth.social.callback', 'facebook'),
                ],
                'services.google'   => [
                    'client_id'     => $setting->get('social_login_google_app_id'),
                    'client_secret' => $setting->get('social_login_google_app_secret'),
                    'redirect'      => route('auth.social.callback', 'google'),
                ],
                'services.github'   => [
                    'client_id'     => $setting->get('social_login_github_app_id'),
                    'client_secret' => $setting->get('social_login_github_app_secret'),
                    'redirect'      => route('auth.social.callback', 'github'),
                ],
                'services.linkedin'   => [
                    'client_id'     => $setting->get('social_login_linkedin_app_id'),
                    'client_secret' => $setting->get('social_login_linkedin_app_secret'),
                    'redirect'      => route('auth.social.callback', 'linkedin'),
                ],
            ]);
        });

        $this->app->register(HookServiceProvider::class);
    }
}
