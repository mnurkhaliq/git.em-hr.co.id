<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOvertimePayrollsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('overtime_payrolls', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('overtime_payroll_type_id')->unsigned()->index('overtime_type_id');
			$table->string('name', 100)->nullable();
			$table->text('custom_calculate', 65535)->nullable();
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
		Schema::drop('overtime_payrolls');
	}

}
