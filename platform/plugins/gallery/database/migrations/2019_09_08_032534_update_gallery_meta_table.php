<?php

use Botble\Blog\Models\Post;
use Botble\Page\Models\Page;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGalleryMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gallery_meta', function (Blueprint $table) {
            $table->renameColumn('content_id', 'reference_id');
            $table->renameColumn('reference', 'reference_type');
        });

        DB::table('gallery_meta')->where('reference_type', 'post')->update(['reference_type' => Post::class]);
        DB::table('gallery_meta')->where('reference_type', 'page')->update(['reference_type' => Page::class]);
        DB::table('gallery_meta')->where('reference_type', 'gallery')->update(['reference_type' => \Botble\Gallery\Models\Gallery::class]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gallery_meta', function (Blueprint $table) {
            $table->renameColumn('reference_id', 'content_id');
            $table->renameColumn('reference_type', 'reference');
        });

        DB::table('gallery_meta')->where('reference', Post::class)->update(['reference' => 'post']);
        DB::table('gallery_meta')->where('reference', Page::class)->update(['reference' => 'page']);
        DB::table('gallery_meta')->where('reference', \Botble\Gallery\Models\Gallery::class)->update(['reference' => 'gallery']);
    }
}
