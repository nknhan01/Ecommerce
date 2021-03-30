<?php

use Botble\Block\Models\Block;
use Illuminate\Database\Migrations\Migration;

class UpdateLanguageMetaForBlock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('language_meta')) {
            DB::table('language_meta')->where('reference_type', 'block')->update(['reference_type' => Block::class]);
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
            DB::table('language_meta')->where('reference_type', Block::class)->update(['reference_type' => 'block']);
        }
    }
}
