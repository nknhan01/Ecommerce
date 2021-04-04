<?php

namespace Platform\Ecommerce\Facades;

use Platform\Ecommerce\Supports\OrderHelper;
use Illuminate\Support\Facades\Facade;

class OrderHelperFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return OrderHelper::class;
    }
}
