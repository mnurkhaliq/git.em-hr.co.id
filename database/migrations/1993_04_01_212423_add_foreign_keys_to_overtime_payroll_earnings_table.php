<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOvertimePayrollEarningsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('overtime_payroll_earnings', function(Blueprint $table)
		{
			$table->foreign('overtime_payroll_id')->references('id')->on('overtime_payrolls')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('payroll_earning_id')->references('id')->on('payroll_earnings')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('overtime_payroll_earnings', function(Blueprint $table)
		{
			$table->dropForeign('overtime_payroll_earnings_overtime_payroll_id_foreign');
			$table->dropForeign('overtime_payroll_earnings_payroll_earning_id_foreign');
		});
	}

}
