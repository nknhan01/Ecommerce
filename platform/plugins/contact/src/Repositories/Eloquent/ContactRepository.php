<?php

namespace Platform\Contact\Repositories\Eloquent;

use Platform\Contact\Enums\ContactStatusEnum;
use Platform\Contact\Repositories\Interfaces\ContactInterface;
use Platform\Support\Repositories\Eloquent\RepositoriesAbstract;

class ContactRepository extends RepositoriesAbstract implements ContactInterface
{
    /**
     * {@inheritDoc}
     */
    public function getUnread($select = ['*'])
    {
        $data = $this->model->where('status', ContactStatusEnum::UNREAD)->select($select)->get();
        $this->resetModel();
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function countUnread()
    {
        $data = $this->model->where('status', ContactStatusEnum::UNREAD)->count();
        $this->resetModel();
        return $data;
    }
}
