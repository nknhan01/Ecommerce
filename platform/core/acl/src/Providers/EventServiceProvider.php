<?php

namespace Platform\ACL\Providers;

use Platform\ACL\Events\RoleAssignmentEvent;
use Platform\ACL\Events\RoleUpdateEvent;
use Platform\ACL\Listeners\LoginListener;
use Platform\ACL\Listeners\RoleAssignmentListener;
use Platform\ACL\Listeners\RoleUpdateListener;
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
        RoleUpdateEvent::class     => [
            RoleUpdateListener::class,
        ],
        RoleAssignmentEvent::class => [
            RoleAssignmentListener::class,
        ],
        Login::class               => [
            LoginListener::class,
        ],
    ];
}
