<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('stub');
            $table->boolean('online')->default(1);
            $table->timestamps();
        });

        Schema::create('banners_images', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('parent_id')->unsigned()->index()->nullable();
            $table->foreign('parent_id')->references('id')->on('banners_images')->onDelete('cascade');

            $table->integer('banner_id')->unsigned()->index()->nullable();
            $table->foreign('banner_id')->references('id')->on('banners')->onDelete('cascade');

            $table->string('title');

            $table->boolean('online')->default(1);
            $table->integer('weight')->unsigned()->default(0);

            $table->integer('attachment_id')->unsigned()->nullable();
            $table->foreign('attachment_id')->references('id')->on('attachments')->onDelete('cascade');

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
        Schema::drop('banners_images');
        Schema::drop('banners');
    }
}
