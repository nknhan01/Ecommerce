<?php

namespace Platform\AuditLog\Repositories\Caches;

use Platform\AuditLog\Repositories\Interfaces\AuditLogInterface;
use Platform\Support\Repositories\Caches\CacheAbstractDecorator;

/**
 * @since 16/09/2016 10:55 AM
 */
class AuditLogCacheDecorator extends CacheAbstractDecorator implements AuditLogInterface
{
}
