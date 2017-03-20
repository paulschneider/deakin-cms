<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('metable_type');
            $table->integer('metable_id');
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->index(['metable_type', 'metable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('metas');
    }
}
