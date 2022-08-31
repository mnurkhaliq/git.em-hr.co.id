<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryApprovalTimesheetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('history_approval_timesheet', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('timesheet_period_id')->unsigned()->index('history_approval_timesheet_timesheet_period_id_foreign');
			$table->integer('structure_organization_custom_id')->unsigned()->index('hat_structure_organization_custom_id_foreign');
			$table->integer('setting_approval_level_id')->unsigned()->index('hat_setting_approval_level_id_id_foreign');
			$table->integer('approval_id')->nullable()->index('history_approval_timesheet_approval_id_foreign');
			$table->boolean('is_approved')->nullable();
			$table->dateTime('date_approved')->nullable();
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
		Schema::drop('history_approval_timesheet');
	}

}
