<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus_links', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('parent_id')->unsigned()->index()->nullable();
            $table->foreign('parent_id')->references('id')->on('menus_links')->onDelete('cascade');

            $table->integer('menu_id')->unsigned()->index()->nullable();
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');

            $table->string('title');
            $table->string('route');
            $table->boolean('online')->default(1);
            $table->integer('weight')->unsigned()->default(0);

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
        Schema::drop('menus_links');
    }
}
