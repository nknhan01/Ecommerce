<?php

namespace Botble\Menu\Repositories\Eloquent;

use Botble\Menu\Repositories\Interfaces\MenuNodeInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Query\JoinClause;

class MenuNodeRepository extends RepositoriesAbstract implements MenuNodeInterface
{
    /**
     * {@inheritDoc}
     */
    public function getByMenuId($menuId, $parentId, $select = ['*'])
    {
        $data = $this->model
            ->with(['child', 'reference'])
            ->where([
                'menu_id'   => $menuId,
                'parent_id' => $parentId,
            ])
            ->leftJoin('slugs', function (JoinClause $join) {
                $join->on('slugs.reference_id', '=', 'menu_nodes.reference_id');
                $join->on('slugs.reference_type', '=', 'menu_nodes.reference_type');
            })
            ->select($select)
            ->orderBy('position', 'asc')
            ->get();

        $this->resetModel();

        return $data;
    }
}
