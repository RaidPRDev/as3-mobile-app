<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_user_profile', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('adminuser_id')->unsigned();
            $table->string('profile_image');
            $table->longText('about_me');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('admin_user_profile', function(Blueprint $table) {
           $table->foreign('adminuser_id')->references('id')->on('adminusers')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admin_user_profile');
    }
}
