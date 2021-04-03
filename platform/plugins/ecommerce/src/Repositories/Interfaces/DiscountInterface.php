<?php

namespace Platform\Ecommerce\Repositories\Interfaces;

use Platform\Support\Repositories\Interfaces\RepositoryInterface;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface DiscountInterface extends RepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAvailablePromotions();

    /**
     * @param array $productIds
     * @param array $productCollections
     * @return Eloquent[]|\Illuminate\Database\Eloquent\Collection|Model[]|Collection
     */
    public function getProductPriceBasedOnPromotion(array $productIds = [], array $productCollections = []);
}
