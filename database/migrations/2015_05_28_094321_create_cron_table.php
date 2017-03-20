<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCronTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron_jobs', function (Blueprint $table) {
            $table->increments('id');

            $table->char('min', 5)->default('*');
            $table->char('hour', 5)->default('*');
            $table->char('day_month', 5)->default('*');
            $table->char('month', 5)->default('*');
            $table->char('day_week', 5)->default('*');
            $table->string('command');
            $table->boolean('online')->default(0);

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
        Schema::drop('cron_jobs');
    }
}
