<?php

namespace Platform\Ecommerce\Repositories\Caches;

use Platform\Ecommerce\Repositories\Interfaces\ProductAttributeInterface;
use Platform\Support\Repositories\Caches\CacheAbstractDecorator;

class ProductAttributeCacheDecorator extends CacheAbstractDecorator implements ProductAttributeInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAllWithSelected($productId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
