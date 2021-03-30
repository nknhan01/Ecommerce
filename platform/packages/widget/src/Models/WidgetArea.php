<?php

namespace Botble\Widget\Models;

use Botble\Base\Models\BaseModel;

class WidgetArea extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'widget_areas';

    /**
     * @var array
     */
    protected $fillable = ['belong_to', 'type', 'data'];

    /**
     * @var array
     */
    protected $casts = [
        'data' => 'json',
    ];
}
