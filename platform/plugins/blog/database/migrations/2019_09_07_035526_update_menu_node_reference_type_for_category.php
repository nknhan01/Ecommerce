<?php

use Botble\Blog\Models\Category;
use Illuminate\Database\Migrations\Migration;

class UpdateMenuNodeReferenceTypeForCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('menu_nodes')) {
            DB::table('menu_nodes')->where('reference_type', 'category')->update(['reference_type' => Category::class]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('menu_nodes')) {
            DB::table('menu_nodes')->where('reference_type', Category::class)->update(['reference_type' => 'category']);
        }
    }
}
