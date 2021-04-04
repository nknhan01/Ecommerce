<?php

namespace Platform\Contact\Repositories\Caches;

use Platform\Contact\Repositories\Interfaces\ContactReplyInterface;
use Platform\Support\Repositories\Caches\CacheAbstractDecorator;

class ContactReplyCacheDecorator extends CacheAbstractDecorator implements ContactReplyInterface
{
}
