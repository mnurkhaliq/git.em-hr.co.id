<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTimesheetActivitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('timesheet_activities', function(Blueprint $table)
		{
			$table->foreign('timesheet_category_id')->references('id')->on('timesheet_categories')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('timesheet_activities', function(Blueprint $table)
		{
			$table->dropForeign('timesheet_activities_timesheet_category_id_foreign');
		});
	}

}
