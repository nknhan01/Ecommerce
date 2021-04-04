<?php

namespace Platform\Testimonial\Providers;

use Illuminate\Routing\Events\RouteMatched;
use Platform\Base\Supports\Helper;
use Platform\Base\Traits\LoadAndPublishDataTrait;
use Platform\Testimonial\Models\Testimonial;
use Platform\Testimonial\Repositories\Caches\TestimonialCacheDecorator;
use Platform\Testimonial\Repositories\Eloquent\TestimonialRepository;
use Platform\Testimonial\Repositories\Interfaces\TestimonialInterface;
use Event;
use Illuminate\Support\ServiceProvider;
use Language;

class TestimonialServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(TestimonialInterface::class, function () {
            return new TestimonialCacheDecorator(new TestimonialRepository(new Testimonial));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/testimonial')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web']);

        $this->app->booted(function () {
            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                Language::registerModule([Testimonial::class]);
            }
        });

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-testimonial',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/testimonial::testimonial.name',
                'icon'        => 'far fa-comment-dots',
                'url'         => route('testimonial.index'),
                'permissions' => ['testimonial.index'],
            ]);
        });
    }
}
