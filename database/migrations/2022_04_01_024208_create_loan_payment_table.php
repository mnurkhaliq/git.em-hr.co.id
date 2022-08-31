<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('loan_id')->nullable();
            $table->integer('tenor')->nullable();
            $table->date('due_date')->nullable();
            $table->double('amount')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('payment_type')->nullable();
            $table->text('photo')->nullable();
            $table->text('user_note')->nullable();
            $table->date('payment_date')->nullable();
            $table->integer('approval_user_id')->nullable();
            $table->text('approval_note')->nullable();
            $table->dateTime('approval_date')->nullable();
            $table->timestamps();
            
            $table->foreign('loan_id')->references('id')->on('loan')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('approval_user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_payment');
    }
}
