<?php

namespace Platform\Ecommerce\Models;

use Platform\Base\Models\BaseModel;
use Platform\Base\Supports\Helper;

class StoreLocator extends BaseModel
{

    /**
     * @var string
     */
    protected $table = 'ec_store_locators';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'country',
        'state',
        'city',
        'is_primary',
        'is_shipping_location',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return string
     */
    public function getCountryNameAttribute()
    {
        return Helper::getCountryNameByCode($this->country);
    }
}
