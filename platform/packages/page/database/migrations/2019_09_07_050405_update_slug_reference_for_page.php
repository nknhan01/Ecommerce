<?php

use Botble\Page\Models\Page;
use Illuminate\Database\Migrations\Migration;

class UpdateSlugReferenceForPage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('slugs')->where('reference_type', 'page')->update(['reference_type' => Page::class]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('slugs')->where('reference_type', Page::class)->update(['reference_type' => 'page']);
    }
}
