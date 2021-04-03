<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnusedColumnsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'dob',
                'address',
                'secondary_address',
                'job_position',
                'phone',
                'secondary_phone',
                'secondary_email',
                'gender',
                'website',
                'skype',
                'facebook',
                'twitter',
                'google_plus',
                'youtube',
                'github',
                'interest',
                'about',
                'completed_profile',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dateTime('dob')->nullable();
            $table->string('address', 255)->nullable();
            $table->string('secondary_address', 255)->nullable();
            $table->string('job_position', 60)->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('secondary_phone', 15)->nullable();
            $table->string('secondary_email', 60)->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->string('website', 120)->nullable();
            $table->string('skype', 60)->nullable();
            $table->string('facebook', 120)->nullable();
            $table->string('twitter', 120)->nullable();
            $table->string('google_plus', 120)->nullable();
            $table->string('youtube', 120)->nullable();
            $table->string('github', 120)->nullable();
            $table->string('interest', 255)->nullable();
            $table->string('about', 400)->nullable();
            $table->boolean('completed_profile')->default(0);
        });
    }
}
