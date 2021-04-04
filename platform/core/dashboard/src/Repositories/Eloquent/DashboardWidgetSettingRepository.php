<?php

namespace Platform\Dashboard\Repositories\Eloquent;

use Illuminate\Support\Facades\Auth;
use Platform\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;
use Platform\Support\Repositories\Eloquent\RepositoriesAbstract;

class DashboardWidgetSettingRepository extends RepositoriesAbstract implements DashboardWidgetSettingInterface
{
    /**
     * {@inheritDoc}
     */
    public function getListWidget()
    {
        $data = $this->model
            ->select([
                'id',
                'order',
                'settings',
                'widget_id',
            ])
            ->with('widget')
            ->orderBy('order')
            ->where('user_id', Auth::user()->getKey())
            ->get();

        $this->resetModel();

        return $data;
    }
}
