<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayrollIdToLoanPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_payment', function (Blueprint $table) {
            $table->unsignedInteger('payroll_history_id')->nullable()->after('payment_type');

            $table->foreign('payroll_history_id')->references('id')->on('payroll_history')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_payment', function (Blueprint $table) {
            //
        });
    }
}
