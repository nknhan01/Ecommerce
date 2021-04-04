<?php

namespace Platform\Newsletter\Repositories\Caches;

use Platform\Newsletter\Repositories\Interfaces\NewsletterInterface;
use Platform\Support\Repositories\Caches\CacheAbstractDecorator;

class NewsletterCacheDecorator extends CacheAbstractDecorator implements NewsletterInterface
{

}
