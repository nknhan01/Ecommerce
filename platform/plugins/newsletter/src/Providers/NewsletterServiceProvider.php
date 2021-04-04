<?php

namespace Platform\Newsletter\Providers;

use Platform\Base\Supports\Helper;
use Platform\Base\Traits\LoadAndPublishDataTrait;
use Platform\Newsletter\Models\Newsletter;
use Platform\Newsletter\Repositories\Caches\NewsletterCacheDecorator;
use Platform\Newsletter\Repositories\Eloquent\NewsletterRepository;
use Platform\Newsletter\Repositories\Interfaces\NewsletterInterface;
use EmailHandler;
use Event;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class NewsletterServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->singleton(NewsletterInterface::class, function () {
            return new NewsletterCacheDecorator(
                new NewsletterRepository(new Newsletter)
            );
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/newsletter')
            ->loadAndPublishConfigurations(['permissions', 'email', 'general'])
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->loadAndPublishViews()
            ->loadMigrations();

        $this->app->register(EventServiceProvider::class);

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-newsletter',
                'priority'    => 6,
                'parent_id'   => null,
                'name'        => 'plugins/newsletter::newsletter.name',
                'icon'        => 'far fa-newspaper',
                'url'         => route('newsletter.index'),
                'permissions' => ['newsletter.index'],
            ]);

            EmailHandler::addTemplateSettings(NEWSLETTER_MODULE_SCREEN_NAME, config('plugins.newsletter.email', []));
        });

    }
}
