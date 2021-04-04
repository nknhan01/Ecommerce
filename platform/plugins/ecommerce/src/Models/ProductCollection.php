<?php

namespace Platform\Ecommerce\Models;

use Platform\Base\Enums\BaseStatusEnum;
use Platform\Base\Models\BaseModel;
use Platform\Base\Traits\EnumCastable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductCollection extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ec_product_collections';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
        'is_featured',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (ProductCollection $collection) {
            $collection->discounts()->detach();
        });
    }

    /**
     * @return BelongsToMany
     */
    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'ec_discount_customers', 'customer_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function products()
    {
        return $this
            ->belongsToMany(
                Product::class,
                'ec_product_collection_products',
                'product_collection_id',
                'product_id'
            )
            ->where('is_variation', 0);
    }
}
