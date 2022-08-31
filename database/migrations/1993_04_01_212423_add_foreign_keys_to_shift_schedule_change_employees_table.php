<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToShiftScheduleChangeEmployeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('shift_schedule_change_employees', function(Blueprint $table)
		{
			$table->foreign('shift_schedule_change_id')->references('id')->on('shift_schedule_changes')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('shift_schedule_change_employees', function(Blueprint $table)
		{
			$table->dropForeign('shift_schedule_change_employees_shift_schedule_change_id_foreign');
			$table->dropForeign('shift_schedule_change_employees_user_id_foreign');
		});
	}

}
