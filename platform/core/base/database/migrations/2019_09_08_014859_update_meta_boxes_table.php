<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMetaBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meta_boxes', function (Blueprint $table) {
            $table->renameColumn('content_id', 'reference_id');
        });

        Schema::table('meta_boxes', function (Blueprint $table) {
            $table->renameColumn('reference', 'reference_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meta_boxes', function (Blueprint $table) {
            $table->renameColumn('reference_id', 'content_id');
        });

        Schema::table('meta_boxes', function (Blueprint $table) {
            $table->renameColumn('reference_type', 'reference');
        });
    }
}
