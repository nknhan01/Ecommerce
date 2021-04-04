<?php

namespace Platform\Ecommerce\Repositories\Caches;

use Platform\Ecommerce\Repositories\Interfaces\ShippingInterface;
use Platform\Support\Repositories\Caches\CacheAbstractDecorator;

class ShippingCacheDecorator extends CacheAbstractDecorator implements ShippingInterface
{
}
