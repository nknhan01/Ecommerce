<?php

namespace Platform\Ecommerce\Repositories\Caches;

use Platform\Ecommerce\Repositories\Interfaces\ProductTagInterface;
use Platform\Support\Repositories\Caches\CacheAbstractDecorator;

class ProductTagCacheDecorator extends CacheAbstractDecorator implements ProductTagInterface
{
}
