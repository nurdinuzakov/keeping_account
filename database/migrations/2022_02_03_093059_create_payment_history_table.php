<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_history', function (Blueprint $table) {
            $table->id();
            $table->integer('balanceable_id')->unsigned();
            $table->string('balanceable_type');
            $table->unsignedbigInteger('payment_id');
            $table->date('date');
            $table->float('amount');
            $table->float('balance_history');
            $table->timestamps();

            //            $table->unsignedbigInteger('user_id');
//            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_history');
    }
}
