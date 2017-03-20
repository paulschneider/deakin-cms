<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIconIdToRelatedLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('related_links', function (Blueprint $table) {
            $table->integer('icon_id')->unsigned()->after('link_id')->index()->nullable();
            $table->foreign('icon_id')->references('id')->on('icons')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('related_links', function (Blueprint $table) {
            $table->dropForeign('related_links_icon_id_foreign');
            $table->dropColumn('icon_id');
        });
    }
}
