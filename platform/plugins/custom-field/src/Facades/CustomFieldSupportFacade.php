<?php

namespace Platform\CustomField\Facades;

use Illuminate\Support\Facades\Facade;
use Platform\CustomField\Support\CustomFieldSupport;

class CustomFieldSupportFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return CustomFieldSupport::class;
    }
}
