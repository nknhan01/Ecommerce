<?php

namespace Platform\Ecommerce\Models;

use Platform\Base\Models\BaseModel;
use Platform\Ecommerce\Repositories\Interfaces\ShippingRuleInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipping extends BaseModel
{

    /**
     * @var string
     */
    protected $table = 'ec_shipping';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'country',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (Shipping $shipping) {
            app(ShippingRuleInterface::class)->deleteBy(['shipping_id' => $shipping->id]);
        });
    }

    /**
     * @return HasMany
     */
    public function rules()
    {
        return $this->hasMany(ShippingRule::class, 'shipping_id');
    }
}
