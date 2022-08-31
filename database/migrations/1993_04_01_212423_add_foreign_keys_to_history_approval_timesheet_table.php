<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToHistoryApprovalTimesheetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('history_approval_timesheet', function(Blueprint $table)
		{
			$table->foreign('setting_approval_level_id', 'hat_setting_approval_level_id_id_foreign')->references('id')->on('setting_approval_level')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('structure_organization_custom_id', 'hat_structure_organization_custom_id_foreign')->references('id')->on('structure_organization_custom')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('approval_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('timesheet_period_id')->references('id')->on('timesheet_periods')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('history_approval_timesheet', function(Blueprint $table)
		{
			$table->dropForeign('hat_setting_approval_level_id_id_foreign');
			$table->dropForeign('hat_structure_organization_custom_id_foreign');
			$table->dropForeign('history_approval_timesheet_approval_id_foreign');
			$table->dropForeign('history_approval_timesheet_timesheet_period_id_foreign');
		});
	}

}
