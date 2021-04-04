<?php

namespace Platform\Base\Facades;

use Platform\Base\Supports\Assets;
use Illuminate\Support\Facades\Facade;

class AssetsFacade extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Assets::class;
    }
}
