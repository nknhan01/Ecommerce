<?php

use Botble\Gallery\Models\Gallery as GalleryModel;
use Illuminate\Database\Migrations\Migration;

class UpdateLanguageMetaForGallery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('language_meta')) {
            DB::table('language_meta')->where('reference_type', 'gallery')->update(['reference_type' => GalleryModel::class]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('language_meta')) {
            DB::table('language_meta')->where('reference_type', GalleryModel::class)->update(['reference_type' => 'gallery']);
        }
    }
}
