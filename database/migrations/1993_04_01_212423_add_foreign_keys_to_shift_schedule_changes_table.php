<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToShiftScheduleChangesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('shift_schedule_changes', function(Blueprint $table)
		{
			$table->foreign('shift_id')->references('id')->on('shift')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('shift_schedule_changes', function(Blueprint $table)
		{
			$table->dropForeign('shift_schedule_changes_shift_id_foreign');
		});
	}

}
