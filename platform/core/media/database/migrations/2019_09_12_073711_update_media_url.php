<?php

use Botble\Media\Models\MediaFile;
use Illuminate\Database\Migrations\Migration;

class UpdateMediaUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $files = MediaFile::get();

        foreach ($files as $file) {
            $url = ltrim($file->url);
            $url = str_replace('storage/', '', $url);
            $url = ltrim($url);
            $file->url = $url;
            $file->save();
        }
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
