<?php

use Botble\Page\Models\Page;
use Illuminate\Database\Migrations\Migration;

class UpdateMetaBoxDataForPage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('meta_boxes')->where('reference_type', 'page')->update(['reference_type' => Page::class]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('meta_boxes')->where('reference_type', Page::class)->update(['reference_type' => 'page']);
    }
}
