<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLangMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('language_meta', function (Blueprint $table) {
            $table->renameColumn('lang_meta_content_id', 'reference_id');
            $table->renameColumn('lang_meta_reference', 'reference_type');
        });

        DB::table('language_meta')->where('reference_type', 'page')->update(['reference_type' => 'Botble\Page\Models\Page']);
        DB::table('language_meta')->where('reference_type', 'menu')->update(['reference_type' => 'Botble\Menu\Models\Menu']);
        DB::table('language_meta')->where('reference_type', 'menu_location')->update(['reference_type' => 'Botble\Menu\Models\MenuLocation']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('language_meta')->where('reference_type', 'Botble\Menu\Models\Menu')->update(['reference_type' => 'menu']);
        DB::table('language_meta')->where('reference_type', 'Botble\Menu\Models\MenuLocation')->update(['reference_type' => 'menu_location']);
        DB::table('language_meta')->where('reference_type', 'Botble\Page\Models\Page')->update(['reference_type' => 'page']);

        Schema::table('language_meta', function (Blueprint $table) {
            $table->renameColumn('reference_id', 'lang_meta_content_id');
            $table->renameColumn('reference_type', 'lang_meta_reference');
        });
    }
}
