<?php

namespace Platform\Ecommerce\Repositories\Eloquent;

use Platform\Ecommerce\Repositories\Interfaces\BrandInterface;
use Platform\Support\Repositories\Eloquent\RepositoriesAbstract;

class BrandRepository extends RepositoriesAbstract implements BrandInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAll(array $condition = [])
    {
        $data = $this->model
            ->where($condition)
            ->orderBy('is_featured', 'DESC')
            ->orderBy('name', 'ASC')
            ->get();

        $this->resetModel();

        return $data;
    }
}
