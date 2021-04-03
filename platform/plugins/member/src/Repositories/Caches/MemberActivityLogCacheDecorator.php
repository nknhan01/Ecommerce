<?php

namespace Platform\Member\Repositories\Caches;

use Platform\Member\Repositories\Interfaces\MemberActivityLogInterface;
use Platform\Support\Repositories\Caches\CacheAbstractDecorator;

class MemberActivityLogCacheDecorator extends CacheAbstractDecorator implements MemberActivityLogInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAllLogs($memberId, $paginate = 10)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
