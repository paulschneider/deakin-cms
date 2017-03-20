<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('online')->default(1);
            $table->string('slug');

            $table->integer('link_id')->unsigned()->index()->nullable();
            $table->foreign('link_id')->references('id')->on('menus_links')->onDelete('SET NULL');

            $table->timestamps();
        });

        Schema::create('page_revisions', function (Blueprint $table) {
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
            $table->foreign('entity_id')->references('id')->on('pages')->onDelete('cascade');

            $table->timestamps();
        });

        // Loop back and set the foreign key.
        Schema::table('pages', function (Blueprint $table) {
            $table->integer('revision_id')->after('id')->index()->unsigned()->nullable();
            $table->foreign('revision_id')->references('id')->on('page_revisions')->onDelete('SET NULL');
        });

        Schema::create('block_page_revision', function ($table) {
            $table->integer('block_id')->unsigned()->index();
            $table->integer('page_revision_id')->unsigned()->index();
            $table->string('type');
            $table->integer('weight');
            $table->foreign('block_id')->references('id')->on('blocks')->onDelete('cascade');
            $table->foreign('page_revision_id')->references('id')->on('page_revisions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign('pages_revision_id_foreign');
        });

        Schema::table('page_revisions', function (Blueprint $table) {
            $table->dropForeign('page_revisions_entity_id_foreign');
            $table->dropForeign('page_revisions_user_id_foreign');
        });

        Schema::drop('block_page_revision');
        Schema::drop('page_revisions');
        Schema::drop('pages');
    }
}
