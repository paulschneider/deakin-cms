<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEntityToCron extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cron_jobs', function (Blueprint $table) {
            $table->integer('entity_id')->unsigned()->nullable()->after('id');
            $table->string('entity_type')->nullable()->after('id');
            $table->boolean('once')->default(true)->after('online');
            $table->integer('year')->unsigned()->nullable()->after('day_week');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cron_jobs', function (Blueprint $table) {
            $table->dropColumn('entity_id');
            $table->dropColumn('entity_type');
            $table->dropColumn('once');
            $table->dropColumn('year');
        });
    }
}
