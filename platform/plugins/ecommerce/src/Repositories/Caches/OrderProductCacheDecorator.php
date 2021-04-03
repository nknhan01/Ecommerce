<?php

namespace Platform\Ecommerce\Repositories\Caches;

use Platform\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Platform\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderProductCacheDecorator extends CacheAbstractDecorator implements OrderProductInterface
{
}
