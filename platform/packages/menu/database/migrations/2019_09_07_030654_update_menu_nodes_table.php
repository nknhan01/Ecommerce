<?php

use Botble\Page\Models\Page;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMenuNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_nodes', function (Blueprint $table) {
            $table->renameColumn('related_id', 'reference_id');
        });

        Schema::table('menu_nodes', function (Blueprint $table) {
            $table->renameColumn('type', 'reference_type');
        });

        Schema::table('menu_nodes', function (Blueprint $table) {
            $table->integer('reference_id')->unsigned()->nullable()->change();
            $table->string('reference_type', 255)->nullable()->change();
        });

        DB::table('menu_nodes')->where('reference_type', 'custom-link')->update(['reference_type' => null]);
        DB::table('menu_nodes')->where('reference_type', 'page')->update(['reference_type' => Page::class]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('menu_nodes')->where('reference_type', null)->update(['reference_type' => 'custom-link']);
        DB::table('menu_nodes')->where('reference_type', Page::class)->update(['reference_type' => 'page']);

        Schema::table('menu_nodes', function (Blueprint $table) {
            $table->renameColumn('reference_id', 'related_id');
        });

        Schema::table('menu_nodes', function (Blueprint $table) {
            $table->renameColumn('reference_type', 'type');
        });

        Schema::table('menu_nodes', function (Blueprint $table) {
            $table->string('type', 60)->change();
        });
    }
}
