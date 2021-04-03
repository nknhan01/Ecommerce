<?php

namespace Platform\RequestLog\Providers;

use Platform\RequestLog\Events\RequestHandlerEvent;
use Platform\RequestLog\Listeners\RequestHandlerListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        RequestHandlerEvent::class => [
            RequestHandlerListener::class,
        ],
    ];
}
