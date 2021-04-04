<?php

namespace Platform\Ecommerce\Repositories\Caches;

use Platform\Ecommerce\Repositories\Interfaces\ProductCollectionInterface;
use Platform\Support\Repositories\Caches\CacheAbstractDecorator;

class ProductCollectionCacheDecorator extends CacheAbstractDecorator implements ProductCollectionInterface
{
    /**
     * {@inheritDoc}
     */
    public function createSlug($name, $id)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
