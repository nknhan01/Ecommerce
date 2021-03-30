<?php

namespace Botble\Slug\Traits;

use Botble\Slug\Models\Slug;
use Eloquent;
use Illuminate\Contracts\Routing\UrlGenerator;

/**
 * @mixin Eloquent
 */
trait SlugTrait
{
    /**
     * @return string
     */
    public function slugable()
    {
        return $this->morphOne(Slug::class, 'reference');
    }

    /**
     * @return string
     */
    public function getSlugAttribute()
    {
        return $this->slugable ? $this->slugable->key : '';
    }

    /**
     * @return int
     */
    public function getSlugIdAttribute()
    {
        return $this->slugable ? $this->slugable->id : '';
    }

    /**
     * @return UrlGenerator|string
     */
    public function getUrlAttribute()
    {
        $prefix = $this->slugable ? $this->slugable->prefix : null;
        $prefix = apply_filters(FILTER_SLUG_PREFIX, $prefix);

        if (!$this->slug) {
            return url('');
        }

        return url($prefix ? $prefix . '/' . $this->slug : $this->slug);
    }
}
