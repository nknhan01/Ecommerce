<?php

namespace Platform\AuditLog\Providers;

use Platform\AuditLog\Events\AuditHandlerEvent;
use Platform\AuditLog\Listeners\AuditHandlerListener;
use Platform\AuditLog\Listeners\CreatedContentListener;
use Platform\AuditLog\Listeners\DeletedContentListener;
use Platform\AuditLog\Listeners\LoginListener;
use Platform\AuditLog\Listeners\UpdatedContentListener;
use Platform\Base\Events\CreatedContentEvent;
use Platform\Base\Events\DeletedContentEvent;
use Platform\Base\Events\UpdatedContentEvent;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AuditHandlerEvent::class   => [
            AuditHandlerListener::class,
        ],
        Login::class               => [
            LoginListener::class,
        ],
        UpdatedContentEvent::class => [
            UpdatedContentListener::class,
        ],
        CreatedContentEvent::class => [
            CreatedContentListener::class,
        ],
        DeletedContentEvent::class => [
            DeletedContentListener::class,
        ],
    ];
}
