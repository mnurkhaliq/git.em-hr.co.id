<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftScheduleChangesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shift_schedule_changes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('shift_id')->unsigned()->index('shift_schedule_changes_shift_id_foreign');
			$table->date('change_date')->nullable();
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
		Schema::drop('shift_schedule_changes');
	}

}
