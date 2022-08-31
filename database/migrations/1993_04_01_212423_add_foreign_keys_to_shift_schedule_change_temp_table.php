<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToShiftScheduleChangeTempTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('shift_schedule_change_temp', function(Blueprint $table)
		{
			$table->foreign('shift_id')->references('id')->on('shift')->onUpdate('CASCADE')->onDelete('CASCADE');
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
		Schema::table('shift_schedule_change_temp', function(Blueprint $table)
		{
			$table->dropForeign('shift_schedule_change_temp_shift_id_foreign');
			$table->dropForeign('shift_schedule_change_temp_user_id_foreign');
		});
	}

}
