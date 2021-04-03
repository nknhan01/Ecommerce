<?php

namespace Platform\Ecommerce\Models;

use Platform\Base\Enums\BaseStatusEnum;
use Platform\Base\Models\BaseModel;
use Platform\Base\Traits\EnumCastable;

class Tax extends BaseModel
{
    use EnumCastable;

    /**
     * @var string
     */
    protected $table = 'ec_taxes';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'percentage',
        'priority',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
