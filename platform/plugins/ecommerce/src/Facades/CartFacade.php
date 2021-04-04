<?php

namespace Platform\Ecommerce\Facades;

use Platform\Ecommerce\Cart\Cart;
use Illuminate\Support\Facades\Facade;

class CartFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Cart::class;
    }
}
