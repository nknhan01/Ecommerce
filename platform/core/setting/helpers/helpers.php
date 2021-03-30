<?php

use Botble\Setting\Facades\SettingFacade;

if (!function_exists('setting')) {
    /**
     * Get the setting instance.
     *
     * @param $key
     * @param $default
     * @return array|\Botble\Setting\Supports\SettingStore|string|null
     */
    function setting($key = null, $default = null)
    {
        if (!empty($key)) {
            try {
                return Setting::get($key, $default);
            } catch (Exception $exception) {
                return $default;
            }
        }

        return SettingFacade::getFacadeRoot();
    }
}

if (!function_exists('get_setting_email_template_content')) {
    /**
     * Get content of email template if module need to config email template
     * @param $type string type of module is system or plugins
     * @param $module string
     * @param $templateKey string key is config in config.email.templates.$key
     * @return bool|mixed|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    function get_setting_email_template_content($type, $module, $templateKey)
    {
        $defaultPath = platform_path($type . '/' . $module . '/resources/email-templates/' . $templateKey . '.tpl');
        $storagePath = get_setting_email_template_path($module, $templateKey);

        if ($storagePath != null && File::exists($storagePath)) {
            return get_file_data($storagePath, false);
        }

        return File::exists($defaultPath) ? get_file_data($defaultPath, false) : '';
    }
}

if (!function_exists('get_setting_email_template_path')) {
    /**
     * Get user email template path in storage file
     * @param $module string
     * @param $templateKey string key is config in config.email.templates.$key
     * @return string
     */
    function get_setting_email_template_path($module, $templateKey)
    {
        return storage_path('app/email-templates/' . $module . '/' . $templateKey . '.tpl');
    }
}

if (!function_exists('get_setting_email_subject_key')) {
    /**
     * get email subject key for setting() function
     * @param $module string
     * @param $templateKey string
     * @return string
     */
    function get_setting_email_subject_key($type, $module, $templateKey)
    {
        return $type . '_' . $module . '_' . $templateKey . '_subject';
    }
}

if (!function_exists('get_setting_email_subject')) {
    /**
     * Get email template subject value
     * @param $type : plugins or core
     * @param $name : name of plugin or core component
     * @param $mail_key : define in config/email/templates
     * @return array|\Botble\Setting\Supports\SettingStore|null|string
     */
    function get_setting_email_subject($type, $module, $templateKey)
    {
        $subject = setting(get_setting_email_subject_key($type, $module, $templateKey),
            trans(config($type . '.' . $module . '.email.templates.' . $templateKey . '.subject',
                '')));

        return $subject;
    }
}

if (!function_exists('get_setting_email_status_key')) {
    /**
     * Get email on or off status key for setting() function
     * @param $type
     * @param $module
     * @param $templateKey
     * @return string
     */
    function get_setting_email_status_key($type, $module, $templateKey)
    {
        return $type . '_' . $module . '_' . $templateKey . '_' . 'status';
    }
}

if (!function_exists('get_setting_email_status')) {
    /**
     * @param $type
     * @param $module
     * @param $templateKey
     * @return array|\Botble\Setting\Supports\SettingStore|null|string
     */
    function get_setting_email_status($type, $module, $templateKey)
    {
        return setting(get_setting_email_status_key($type, $module, $templateKey), true);
    }
}
