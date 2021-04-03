<?php

namespace Platform\Menu\Models;

use Platform\Base\Enums\BaseStatusEnum;
use Platform\Base\Traits\EnumCastable;
use Platform\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends BaseModel
{

    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    /**
     * @return HasMany
     */
    public function menuNodes()
    {
        return $this->hasMany(MenuNode::class, 'menu_id');
    }

    /**
     * @return HasMany
     */
    public function locations()
    {
        return $this->hasMany(MenuLocation::class, 'menu_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Menu $menu) {
            MenuNode::where('menu_id', $menu->id)->delete();
        });
    }
}
