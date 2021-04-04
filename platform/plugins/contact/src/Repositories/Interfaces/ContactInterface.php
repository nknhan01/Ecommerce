<?php

namespace Platform\Contact\Repositories\Interfaces;

use Platform\Support\Repositories\Interfaces\RepositoryInterface;

interface ContactInterface extends RepositoryInterface
{
    /**
     * @param array $select
     * @return mixed
     */
    public function getUnread($select = ['*']);

    /**
     * @return int
     */
    public function countUnread();
}
