<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
//            $table->unsignedbigInteger('user_id');
            $table->unsignedbigInteger('category_id');
            $table->unsignedbigInteger('paymentMethod_id');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->date('date');
            $table->string('responsible_person');
            $table->double('amount');
            $table->string('receipt_photo')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('expenses');
    }
}
