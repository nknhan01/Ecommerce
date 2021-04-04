<?php

namespace Platform\Base\Facades;

use Platform\Base\Supports\MetaBox;
use Illuminate\Support\Facades\Facade;

class MetaBoxFacade extends Facade
{

    /**
     * @return string
     * @since 2.2
     */
    protected static function getFacadeAccessor()
    {
        return MetaBox::class;
    }
}
