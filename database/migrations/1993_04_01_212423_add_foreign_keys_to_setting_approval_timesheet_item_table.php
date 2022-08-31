<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSettingApprovalTimesheetItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('setting_approval_timesheet_item', function(Blueprint $table)
		{
			$table->foreign('setting_approval_level_id', 'sat_setting_approval_level_id_foreign')->references('id')->on('setting_approval_level')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('structure_organization_custom_id', 'sat_structure_organization_custom_id_foreign')->references('id')->on('structure_organization_custom')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('setting_approval_id')->references('id')->on('setting_approval')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('setting_approval_timesheet_item', function(Blueprint $table)
		{
			$table->dropForeign('sat_setting_approval_level_id_foreign');
			$table->dropForeign('sat_structure_organization_custom_id_foreign');
			$table->dropForeign('setting_approval_timesheet_item_setting_approval_id_foreign');
		});
	}

}
