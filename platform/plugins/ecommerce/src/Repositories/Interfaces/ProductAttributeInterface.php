<?php

namespace Platform\Ecommerce\Repositories\Interfaces;

use Platform\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\Collection;

interface ProductAttributeInterface extends RepositoryInterface
{
    /**
     * @param int $productId
     * @return Collection
     */
    public function getAllWithSelected($productId);
}
