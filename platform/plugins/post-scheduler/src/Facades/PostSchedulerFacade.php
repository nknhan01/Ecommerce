<?php

namespace Platform\PostScheduler\Facades;

use Platform\PostScheduler\Supports\PostScheduler;
use Illuminate\Support\Facades\Facade;

class PostSchedulerFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PostScheduler::class;
    }
}
