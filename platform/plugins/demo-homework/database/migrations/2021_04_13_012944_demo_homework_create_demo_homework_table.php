<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class DemoHomeworkCreateDemoHomeworkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_authors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->date('date_of_birth');
            $table->string('addres', 255);
            $table->string('gender', 60)->default('male');
            $table->integer('publishing_houses_id');
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });
        Schema::create('app_posts_demo', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('description', 400)->nullable();
            $table->integer('author_id');
            $table->timestamps();
        });
        Schema::create('app_publishing_houses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('addres', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demo_homeworks');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('publishing_houses');
    }
}
