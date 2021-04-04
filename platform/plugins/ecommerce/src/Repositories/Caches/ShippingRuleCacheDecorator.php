<?php

namespace Platform\Ecommerce\Repositories\Caches;

use Platform\Ecommerce\Repositories\Interfaces\ShippingRuleInterface;
use Platform\Support\Repositories\Caches\CacheAbstractDecorator;

class ShippingRuleCacheDecorator extends CacheAbstractDecorator implements ShippingRuleInterface
{
}
