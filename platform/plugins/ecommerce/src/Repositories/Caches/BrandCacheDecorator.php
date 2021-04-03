<?php

namespace Platform\Ecommerce\Repositories\Caches;

use Platform\Ecommerce\Repositories\Interfaces\BrandInterface;
use Platform\Support\Repositories\Caches\CacheAbstractDecorator;

class BrandCacheDecorator extends CacheAbstractDecorator implements BrandInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAll(array $condition = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
