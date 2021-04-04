<?php

namespace Platform\Ecommerce\Models;

use Platform\Base\Models\BaseModel;
use Platform\Base\Supports\Helper;

class Address extends BaseModel
{

    /**
     * @var string
     */
    protected $table = 'ec_customer_addresses';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'country',
        'state',
        'city',
        'address',
        'zip_code',
        'customer_id',
        'is_default',
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
