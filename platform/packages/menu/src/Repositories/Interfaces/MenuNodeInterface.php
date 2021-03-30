<?php

namespace Botble\Menu\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface MenuNodeInterface extends RepositoryInterface
{
    /**
     * @param $parentId
     * @param null array
     * @return array|Collection|static[]
     */
    public function getByMenuId($menuId, $parentId, $select = ['*']);
}
