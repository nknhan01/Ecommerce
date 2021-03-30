<?php

use Illuminate\Database\Migrations\Migration;

class ChangeSiteTitleFromSettingsToThemeOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function up()
    {
        ThemeOption::setOption('site_title', setting('site_title'));
        ThemeOption::setOption('show_site_name', setting('show_site_name'));
        ThemeOption::setOption('seo_title', setting('seo_title'));
        ThemeOption::setOption('seo_description', setting('seo_description'));

        Setting::save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
