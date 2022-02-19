<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedbigInteger('income_id')->nullable();
            $table->unsignedBigInteger('expense_id')->nullable();
            $table->date('date');
            $table->float('balance');
            $table->timestamps();

            $table->foreign('income_id')->references('id')->on('incomes')->cascadeOnDelete();
            $table->foreign('expense_id')->references('id')->on('expenses')->cascadeOnDelete();

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
        Schema::dropIfExists('balances');
    }
}
