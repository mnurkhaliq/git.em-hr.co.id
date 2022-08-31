<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollDeductionsEmployeeHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_deductions_employee_history', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('payroll_id')->nullable();
			$table->integer('payroll_deduction_id')->nullable();
			$table->integer('nominal')->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payroll_deductions_employee_history');
	}

}
