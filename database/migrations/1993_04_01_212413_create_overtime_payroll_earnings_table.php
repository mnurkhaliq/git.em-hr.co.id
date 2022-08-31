<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimePayrollEarningsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('overtime_payroll_earnings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('overtime_payroll_id')->unsigned()->index('overtime_payroll_id');
			$table->string('payroll_attribut', 100)->nullable();
			$table->integer('payroll_earning_id')->unsigned()->nullable()->index('payroll_earning_id');
			$table->integer('payroll_earning_value')->nullable();
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
		Schema::drop('overtime_payroll_earnings');
	}

}
