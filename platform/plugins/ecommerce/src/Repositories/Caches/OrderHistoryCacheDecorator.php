<?php

namespace Platform\Ecommerce\Repositories\Caches;

use Platform\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Platform\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderHistoryCacheDecorator extends CacheAbstractDecorator implements OrderHistoryInterface
{
}
