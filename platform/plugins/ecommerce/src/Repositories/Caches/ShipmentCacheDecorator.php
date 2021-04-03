<?php

namespace Platform\Ecommerce\Repositories\Caches;

use Platform\Ecommerce\Repositories\Interfaces\ShipmentInterface;
use Platform\Support\Repositories\Caches\CacheAbstractDecorator;

class ShipmentCacheDecorator extends CacheAbstractDecorator implements ShipmentInterface
{
}
