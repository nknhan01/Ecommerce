<?php

namespace Platform\Ecommerce\Repositories\Caches;

use Platform\Ecommerce\Repositories\Interfaces\TaxInterface;
use Platform\Support\Repositories\Caches\CacheAbstractDecorator;

class TaxCacheDecorator extends CacheAbstractDecorator implements TaxInterface
{
}
