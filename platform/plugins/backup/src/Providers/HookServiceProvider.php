<?php

namespace Botble\Backup\Providers;

use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (app()->environment('demo')) {
            add_filter(DASHBOARD_FILTER_ADMIN_NOTIFICATIONS, [$this, 'registerAdminAlert'], 5, 1);
        }

        add_filter(BASE_FILTER_AFTER_SETTING_CONTENT, [$this, 'addBackupSetting'], 199, 1);
    }

    /**
     * @param string $alert
     * @return string
     * @throws \Throwable
     */
    public function registerAdminAlert($alert): string
    {
        return $alert . view('plugins/backup::partials.admin-alert')->render();
    }

    /**
     * @param null $data
     * @return string
     * @throws \Throwable
     */
    public function addBackupSetting($data = null): string
    {
        return $data . view('plugins/backup::setting')->render();
    }
}
