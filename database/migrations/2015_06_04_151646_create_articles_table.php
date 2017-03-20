<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('online')->default(1);
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('article_revisions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status');
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->string('user_ip')->nullable();
            $table->integer('entity_id')->unsigned()->index();

            // Custom
            $table->string('title');
            $table->text('body')->nullable();
            $table->text('summary')->nullable();

            // Hook back.
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('entity_id')->references('id')->on('articles')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('article_revision_term', function ($table) {
            $table->integer('article_revision_id')->unsigned()->index();
            $table->integer('term_id')->unsigned()->index();
            $table->string('type');

            $table->foreign('article_revision_id')->references('id')->on('article_revisions')->onDelete('cascade');
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
        });

        // Loop back and set the foreign key.
        Schema::table('articles', function (Blueprint $table) {
            $table->integer('revision_id')->after('id')->index()->unsigned()->nullable();
            $table->foreign('revision_id')->references('id')->on('article_revisions')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign('articles_revision_id_foreign');
        });

        Schema::table('article_revisions', function (Blueprint $table) {
            $table->dropForeign('article_revisions_entity_id_foreign');
            $table->dropForeign('article_revisions_user_id_foreign');
        });

        Schema::drop('article_revision_term');
        Schema::drop('article_revisions');
        Schema::drop('articles');
    }
}
