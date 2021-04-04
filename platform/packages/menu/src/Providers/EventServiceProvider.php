<?php

namespace Platform\Menu\Providers;

use Platform\Base\Events\DeletedContentEvent;
use Platform\Menu\Listeners\DeleteMenuNodeListener;
use Platform\Menu\Listeners\UpdateMenuNodeUrlListener;
use Platform\Slug\Events\UpdatedSlugEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UpdatedSlugEvent::class    => [
            UpdateMenuNodeUrlListener::class,
        ],
        DeletedContentEvent::class => [
            DeleteMenuNodeListener::class,
        ],
    ];
}
