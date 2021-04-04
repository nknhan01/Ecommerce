<?php

namespace Platform\Ecommerce\Repositories\Interfaces;

use Platform\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\Collection;

interface CurrencyInterface extends RepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAllCurrencies();
}
