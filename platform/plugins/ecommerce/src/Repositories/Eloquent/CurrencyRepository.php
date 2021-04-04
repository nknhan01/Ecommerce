<?php

namespace Platform\Ecommerce\Repositories\Eloquent;

use Platform\Ecommerce\Repositories\Interfaces\CurrencyInterface;
use Platform\Support\Repositories\Eloquent\RepositoriesAbstract;

class CurrencyRepository extends RepositoriesAbstract implements CurrencyInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAllCurrencies()
    {
        $data = $this->model
            ->orderBy('order', 'ASC')
            ->get();

        $this->resetModel();

        return $data;
    }
}
