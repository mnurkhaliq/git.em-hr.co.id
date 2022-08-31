<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimesheetPeriodsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('timesheet_periods', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable()->index('timesheet_periods_user_id_foreign');
			$table->date('start_date')->nullable();
			$table->date('end_date')->nullable();
			$table->boolean('status')->nullable();
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
		Schema::drop('timesheet_periods');
	}

}
