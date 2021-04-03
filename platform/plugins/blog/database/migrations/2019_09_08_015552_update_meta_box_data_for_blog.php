<?php

use Platform\Blog\Models\Category;
use Platform\Blog\Models\Post;
use Platform\Blog\Models\Tag;
use Illuminate\Database\Migrations\Migration;

class UpdateMetaBoxDataForBlog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('meta_boxes')->where('reference_type', 'post')->update(['reference_type' => Post::class]);
        DB::table('meta_boxes')->where('reference_type', 'category')->update(['reference_type' => Category::class]);
        DB::table('meta_boxes')->where('reference_type', 'tag')->update(['reference_type' => Tag::class]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('meta_boxes')->where('reference_type', Post::class)->update(['reference_type' => 'post']);
        DB::table('meta_boxes')->where('reference_type', Category::class)->update(['reference_type' => 'category']);
        DB::table('meta_boxes')->where('reference_type', Tag::class)->update(['reference_type' => 'tag']);
    }
}
