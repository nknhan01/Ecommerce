<?php

namespace Platform\Base\Facades;

use Platform\Base\Supports\MacroableModels;
use Illuminate\Support\Facades\Facade;

class MacroableModelsFacade extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return MacroableModels::class;
    }
}
