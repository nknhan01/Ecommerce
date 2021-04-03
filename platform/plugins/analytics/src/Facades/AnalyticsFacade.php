<?php

namespace Platform\Analytics\Facades;

use Platform\Analytics\Analytics;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Platform\Analytics\Analytics
 */
class AnalyticsFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Analytics::class;
    }
}
