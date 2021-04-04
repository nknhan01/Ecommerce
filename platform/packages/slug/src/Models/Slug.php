<?php

namespace Platform\Slug\Models;

use Platform\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slug extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'slugs';

    /**
     * @var array
     */
    protected $fillable = [
        'key',
        'reference_type',
        'reference_id',
        'prefix',
    ];

    /**
     * @return BelongsTo
     */
    public function reference()
    {
        return $this->morphTo();
    }
}
