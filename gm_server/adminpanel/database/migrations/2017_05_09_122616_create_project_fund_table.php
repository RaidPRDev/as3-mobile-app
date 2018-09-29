<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectFundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_funds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_payment_id');
            $table->char('amount')->length(20);
            $table->integer('project_id');
            $table->integer('user_id');
            $table->integer('refer_id');
            $table->char('goaliemind_amt')->length(20);
            $table->char('refer_amt')->length(20);
            $table->enum('isWithdrawl',['0','1']);
            $table->timestamp('updated_on')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('project_funds');
    }
}
