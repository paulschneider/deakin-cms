<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credentials', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('online')->default(1);
            $table->string('slug');

            $table->integer('link_id')->unsigned()->index()->nullable();
            $table->foreign('link_id')->references('id')->on('menus_links')->onDelete('SET NULL');

            $table->timestamps();
        });

        Schema::create('credential_revisions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status');
            $table->string('entity_color')->nullable();
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->string('user_ip')->nullable();
            $table->integer('entity_id')->unsigned()->index();
            $table->integer('logo_id')->unsigned()->index()->nullable();

            // Custom
            $table->string('title');
            $table->text('body')->nullable();
            $table->text('summary')->nullable();

            // Hook back.
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('entity_id')->references('id')->on('credentials')->onDelete('cascade');
            $table->foreign('logo_id')->references('id')->on('attachments')->onDelete('SET NULL');

            $table->timestamps();
        });

        // Loop back and set the foreign key.
        Schema::table('credentials', function (Blueprint $table) {
            $table->integer('revision_id')->after('id')->index()->unsigned()->nullable();
            $table->foreign('revision_id')->references('id')->on('credential_revisions')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credentials', function (Blueprint $table) {
            $table->dropForeign('credentials_revision_id_foreign');
        });

        Schema::table('credential_revisions', function (Blueprint $table) {
            $table->dropForeign('credential_revisions_entity_id_foreign');
            $table->dropForeign('credential_revisions_user_id_foreign');
            $table->dropForeign('credential_revisions_logo_id_foreign');
        });

        Schema::drop('credential_revisions');
        Schema::drop('credentials');
    }
}
