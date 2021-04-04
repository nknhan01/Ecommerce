<?php

namespace Platform\Ecommerce\Models;

use Platform\Base\Models\BaseModel;

class GroupedProduct extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ec_grouped_products';

    /**
     * @var array
     */
    protected $fillable = [
        'parent_product_id',
        'product_id',
        'fixed_qty',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
