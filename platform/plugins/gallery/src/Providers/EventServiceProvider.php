<?php

namespace Platform\Gallery\Providers;

use Platform\Base\Events\CreatedContentEvent;
use Platform\Base\Events\DeletedContentEvent;
use Platform\Theme\Events\RenderingSiteMapEvent;
use Platform\Base\Events\UpdatedContentEvent;
use Platform\Gallery\Listeners\CreatedContentListener;
use Platform\Gallery\Listeners\DeletedContentListener;
use Platform\Gallery\Listeners\RenderingSiteMapListener;
use Platform\Gallery\Listeners\UpdatedContentListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        RenderingSiteMapEvent::class => [
            RenderingSiteMapListener::class,
        ],
        UpdatedContentEvent::class   => [
            UpdatedContentListener::class,
        ],
        CreatedContentEvent::class   => [
            CreatedContentListener::class,
        ],
        DeletedContentEvent::class   => [
            DeletedContentListener::class,
        ],
    ];
}
