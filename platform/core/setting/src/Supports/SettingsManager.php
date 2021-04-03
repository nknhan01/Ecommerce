<?php

namespace Platform\Setting\Supports;

use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Manager;

class SettingsManager extends Manager
{
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
        return new JsonSettingStore(app('files'));
    }

    /**
     * @return DatabaseSettingStore
     */
    public function createDatabaseDriver()
    {
        $connection = app(DatabaseManager::class)->connection();
        $table = 'settings';
        $keyColumn = 'key';
        $valueColumn = 'value';

        return new DatabaseSettingStore($connection, $table, $keyColumn, $valueColumn);
    }
}
