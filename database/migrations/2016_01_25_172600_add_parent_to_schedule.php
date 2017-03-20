<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParentToSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cron_jobs', function (Blueprint $table) {
            $table->string('parent_type')->after('entity_id')->nullable();
            $table->integer('parent_id')->after('parent_type')->unsigned()->nullable();
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
            $table->dropColumn('parent_type');
            $table->dropColumn('parent_id');
        });
    }
}
