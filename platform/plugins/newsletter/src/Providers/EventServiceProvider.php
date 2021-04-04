<?php

namespace Platform\Newsletter\Providers;

use Platform\Newsletter\Events\SubscribeNewsletterEvent;
use Platform\Newsletter\Listeners\SubscribeNewsletterListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SubscribeNewsletterEvent::class => [
            SubscribeNewsletterListener::class,
        ],
    ];
}
