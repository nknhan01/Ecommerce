<?php

use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Blog\Models\Tag;
use Illuminate\Database\Migrations\Migration;

class UpdateSlugReferenceForBlog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('slugs')) {
            DB::table('slugs')->where('reference_type', 'category')->update(['reference_type' => Category::class]);
            DB::table('slugs')->where('reference_type', 'post')->update(['reference_type' => Post::class]);
            DB::table('slugs')->where('reference_type', 'tag')->update(['reference_type' => Tag::class]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('slugs')) {
            DB::table('slugs')->where('reference_type', Category::class)->update(['reference_type' => 'category']);
            DB::table('slugs')->where('reference_type', Post::class)->update(['reference_type' => 'post']);
            DB::table('slugs')->where('reference_type', Tag::class)->update(['reference_type' => 'tag']);
        }
    }
}
