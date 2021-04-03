<?php

use Platform\Blog\Models\Category;
use Platform\Blog\Models\Post;
use Platform\Blog\Models\Tag;
use Illuminate\Database\Migrations\Migration;

class UpdateLanguageMetaForBlog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('language_meta')) {
            DB::table('language_meta')->where('reference_type', 'post')->update(['reference_type' => Post::class]);
            DB::table('language_meta')->where('reference_type',
                'category')->update(['reference_type' => Category::class]);
            DB::table('language_meta')->where('reference_type', 'tag')->update(['reference_type' => Tag::class]);
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
            DB::table('language_meta')->where('reference_type', Post::class)->update(['reference_type' => 'post']);
            DB::table('language_meta')->where('reference_type',
                Category::class)->update(['reference_type' => 'category']);
            DB::table('language_meta')->where('reference_type', Tag::class)->update(['reference_type' => 'tag']);
        }
    }
}
