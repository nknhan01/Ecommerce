<?php

namespace Botble\Language;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Schema;
use Setting;

class Plugin extends PluginOperationAbstract
{
    public static function activated()
    {
        $setting = setting('activated_plugins');
        $setting = str_replace(',"language"', '', $setting);
        $setting = '["language",' . ltrim($setting, '[');
        Setting::set('activated_plugins', $setting)->save();
    }

    public static function remove()
    {
        Schema::dropIfExists('languages');
        Schema::dropIfExists('language_meta');
    }
}
