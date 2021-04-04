<?php

namespace Platform\Newsletter\Models;

use Platform\Base\Models\BaseModel;
use Platform\Base\Traits\EnumCastable;
use Platform\Newsletter\Enums\NewsletterStatusEnum;

class Newsletter extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'newsletters';

    /**
     * @var array
     */
    protected $fillable = [
        'email',
        'name',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => NewsletterStatusEnum::class,
    ];
}
