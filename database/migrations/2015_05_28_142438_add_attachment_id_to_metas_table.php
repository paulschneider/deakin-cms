<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttachmentIdToMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('metas', function (Blueprint $table) {
            $table->integer('attachment_id')->unsigned()->after('id')->index()->nullable();
            $table->foreign('attachment_id')->references('id')->on('attachments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('metas', function (Blueprint $table) {
            $table->dropForeign('metas_attachment_id_foreign');
            $table->dropColumn('attachment_id');
        });
    }
}
