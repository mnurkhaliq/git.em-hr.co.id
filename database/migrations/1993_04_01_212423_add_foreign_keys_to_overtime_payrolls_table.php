<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOvertimePayrollsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('overtime_payrolls', function(Blueprint $table)
		{
			$table->foreign('overtime_payroll_type_id')->references('id')->on('overtime_payroll_types')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('overtime_payrolls', function(Blueprint $table)
		{
			$table->dropForeign('overtime_payrolls_overtime_payroll_type_id_foreign');
		});
	}

}
