<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftScheduleChangeEmployeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shift_schedule_change_employees', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('shift_schedule_change_id')->unsigned()->index('shift_schedule_change_employees_shift_schedule_change_id_foreign');
			$table->integer('user_id')->index('shift_schedule_change_employees_user_id_foreign');
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
		Schema::drop('shift_schedule_change_employees');
	}

}
