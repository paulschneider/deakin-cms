<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelatedLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('related_links', function (Blueprint $table) {
            $table->increments('id');

            $table->string('related_type');
            $table->integer('related_id')->unsigned()->index();

            $table->string('title');
            $table->string('class')->nullable();
            $table->string('external_url', 1000);

            $table->integer('link_id')->unsigned()->index()->nullable();
            $table->foreign('link_id')->references('id')->on('menus_links')->onDelete('cascade');

            $table->integer('weight')->unsigned();

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
        Schema::drop('related_links');
    }
}
