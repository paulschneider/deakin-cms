<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAliasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aliases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('aliasable_type');
            $table->integer('aliasable_id');
            $table->string('alias');
            $table->timestamps();

            $table->index(['aliasable_type', 'aliasable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('aliases');
    }
}
