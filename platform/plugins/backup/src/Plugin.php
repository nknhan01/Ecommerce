<?php

namespace Platform\Backup;

use Platform\PluginManagement\Abstracts\PluginOperationAbstract;
use File;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        $backupPath = storage_path('app/backup');
        if (File::isDirectory($backupPath)) {
            File::deleteDirectory($backupPath);
        }
    }
}
