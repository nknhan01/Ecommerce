<?php

namespace Platform\Ecommerce\Repositories\Caches;

use Platform\Ecommerce\Repositories\Interfaces\ShipmentHistoryInterface;
use Platform\Support\Repositories\Caches\CacheAbstractDecorator;

class ShipmentHistoryCacheDecorator extends CacheAbstractDecorator implements ShipmentHistoryInterface
{
}
