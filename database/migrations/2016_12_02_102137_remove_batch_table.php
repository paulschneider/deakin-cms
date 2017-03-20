<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveBatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('batches');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid');
            $table->integer('total');
            $table->integer('completed');
            $table->timestamps();
        });
    }
}
