<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToTerms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('terms', function (Blueprint $table) {
            $table->integer('parent_id')->unsigned()->after('id')->index()->nullable();

            $table->foreign('parent_id')->references('id')->on('terms')->onDelete('cascade');
            $table->foreign('vocabulary_id')->references('id')->on('vocabularies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('terms', function (Blueprint $table) {
            $table->dropForeign('terms_vocabulary_id_foreign');
            $table->dropForeign('terms_parent_id_foreign');
            $table->dropColumn('parent_id');
        });
    }
}
