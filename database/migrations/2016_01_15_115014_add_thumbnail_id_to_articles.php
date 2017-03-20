<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThumbnailIdToArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_revisions', function (Blueprint $table) {
            $table->integer('thumbnail_id')->unsigned()->nullable();
            $table->foreign('thumbnail_id')->references('id')->on('attachments')->onDelete('SET NULL');

            $table->integer('image_id')->unsigned()->nullable();
            $table->foreign('image_id')->references('id')->on('attachments')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_revisions', function (Blueprint $table) {
            $table->dropForeign('article_revisions_thumbnail_id_foreign');
            $table->dropColumn('thumbnail_id');

            $table->dropForeign('article_revisions_image_id_foreign');
            $table->dropColumn('image_id');
        });
    }
}
