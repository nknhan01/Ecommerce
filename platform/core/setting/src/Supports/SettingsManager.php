<?php

namespace Botble\Setting\Supports;

use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Manager;
use Illuminate\Foundation\Application;

class SettingsManager extends Manager
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @return string
     */
    public function getDefaultDriver()
    {
        return config('core.setting.general.driver');
    }

    /**
     * @return JsonSettingStore
     */
    public function createJsonDriver()
    {
        return new JsonSettingStore($this->app['files']);
    }

    /**
     * @return DatabaseSettingStore
     */
    public function createDatabaseDriver()
    {
        $connection = $this->app->make(DatabaseManager::class)->connection();
        $table = 'settings';
        $keyColumn = 'key';
        $valueColumn = 'value';

        return new DatabaseSettingStore($connection, $table, $keyColumn, $valueColumn);
    }
}
