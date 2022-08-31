<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->foreign('attendance_cycle_id')->references('id')->on('payroll_cycle')->onUpdate('CASCADE')->onDelete('SET NULL');
			$table->foreign('master_category_visit_id')->references('id')->on('master_category_visit')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('master_visit_type_id')->references('id')->on('master_visit_type')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('overtime_payroll_id')->references('id')->on('overtime_payrolls')->onUpdate('CASCADE')->onDelete('SET NULL');
			$table->foreign('payroll_country_id')->references('id')->on('payroll_country')->onUpdate('CASCADE')->onDelete('SET NULL');
			$table->foreign('payroll_cycle_id')->references('id')->on('payroll_cycle')->onUpdate('CASCADE')->onDelete('SET NULL');
			$table->foreign('payroll_umr_id')->references('id')->on('payroll_umr')->onUpdate('CASCADE')->onDelete('SET NULL');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->dropForeign('users_attendance_cycle_id_foreign');
			$table->dropForeign('users_master_category_visit_id_foreign');
			$table->dropForeign('users_master_visit_type_id_foreign');
			$table->dropForeign('users_overtime_payroll_id_foreign');
			$table->dropForeign('users_payroll_country_id_foreign');
			$table->dropForeign('users_payroll_cycle_id_foreign');
			$table->dropForeign('users_payroll_umr_id_foreign');
		});
	}

}
