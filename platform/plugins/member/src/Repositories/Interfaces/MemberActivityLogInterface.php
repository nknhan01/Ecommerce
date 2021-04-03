<?php

namespace Platform\Member\Repositories\Interfaces;

use Platform\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface MemberActivityLogInterface extends RepositoryInterface
{
    /**
     * @param $memberId
     * @param int $paginate
     * @return Collection
     */
    public function getAllLogs($memberId, $paginate = 10);
}
