<?php

namespace Platform\Ecommerce\Models;

use Platform\Base\Models\BaseModel;
use Platform\Base\Traits\EnumCastable;
use Platform\Ecommerce\Enums\ShippingCodStatusEnum;
use Platform\Ecommerce\Enums\ShippingStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shipment extends BaseModel
{
    use EnumCastable;

    /**
     * @var string
     */
    protected $table = 'ec_shipments';

    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'user_id',
        'weight',
        'shipment_id',
        'note',
        'status',
        'cod_amount',
        'cod_status',
        'cross_checking_status',
        'price',
        'store_id',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status'     => ShippingStatusEnum::class,
        'cod_status' => ShippingCodStatusEnum::class,
    ];

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (Shipment $shipment) {
            ShipmentHistory::where('shipment_id', $shipment->id)->delete();
        });
    }

    /**
     * @return HasOne
     */
    public function store()
    {
        return $this->hasOne(StoreLocator::class, 'id', 'store_id')->withDefault();
    }

    /**
     * @return HasMany
     */
    public function histories()
    {
        return $this->hasMany(ShipmentHistory::class, 'shipment_id');
    }

    /**
     * @return BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class)->withDefault();
    }
}
