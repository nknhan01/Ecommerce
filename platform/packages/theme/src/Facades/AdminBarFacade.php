<?php

namespace Platform\Theme\Facades;

use Platform\Theme\Supports\AdminBar;
use Illuminate\Support\Facades\Facade;

class AdminBarFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return AdminBar::class;
    }
}
