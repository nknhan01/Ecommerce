<?php

namespace Platform\Ecommerce\Repositories\Caches;

use Platform\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Platform\Support\Repositories\Caches\CacheAbstractDecorator;

class CustomerCacheDecorator extends CacheAbstractDecorator implements CustomerInterface
{
}
