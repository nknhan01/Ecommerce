<?php

namespace Platform\Ecommerce\Repositories\Caches;

use Platform\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Platform\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderAddressCacheDecorator extends CacheAbstractDecorator implements OrderAddressInterface
{
}
