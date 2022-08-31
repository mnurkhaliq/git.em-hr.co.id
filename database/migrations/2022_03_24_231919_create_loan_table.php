<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('loan_purpose')->nullable();
            $table->double('plafond')->nullable();
            $table->date('expected_disbursement_date')->nullable();
            $table->date('disbursement_date')->nullable();
            $table->double('amount')->nullable();
            $table->double('calculated_amount')->nullable();
            $table->integer('rate')->nullable();
            $table->double('interest')->nullable();
            $table->tinyInteger('payment_type')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('approval_collateral_receipt_status')->nullable();
            $table->integer('approval_collateral_receipt_user_id')->nullable();
            $table->dateTime('approval_collateral_receipt_date')->nullable();
            $table->text('approval_collateral_receipt_note')->nullable();
            $table->tinyInteger('approval_collateral_physical_status')->nullable();
            $table->integer('approval_collateral_physical_user_id')->nullable();
            $table->dateTime('approval_collateral_physical_date')->nullable();
            $table->text('approval_collateral_physical_note')->nullable();
            $table->tinyInteger('approval_loan_status')->nullable();
            $table->integer('approval_loan_user_id')->nullable();
            $table->dateTime('approval_loan_date')->nullable();
            $table->text('approval_loan_note')->nullable();
            $table->date('first_due_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('approval_collateral_receipt_user_id', 'acr_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign('approval_collateral_physical_user_id', 'acp_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign('approval_loan_user_id', 'al_user_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan');
    }
}
