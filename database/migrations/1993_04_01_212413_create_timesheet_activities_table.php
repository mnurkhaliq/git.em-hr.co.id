<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimesheetActivitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('timesheet_activities', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('timesheet_category_id')->unsigned()->nullable()->index('timesheet_activities_timesheet_category_id_foreign');
			$table->string('name', 191)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->boolean('delete_status')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('timesheet_activities');
	}

}
