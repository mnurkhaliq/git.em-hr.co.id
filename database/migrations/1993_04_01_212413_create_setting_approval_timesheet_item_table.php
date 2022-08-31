<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingApprovalTimesheetItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('setting_approval_timesheet_item', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('setting_approval_id')->unsigned()->index('setting_approval_timesheet_item_setting_approval_id_foreign');
			$table->integer('setting_approval_level_id')->unsigned()->index('sat_setting_approval_level_id_foreign');
			$table->integer('structure_organization_custom_id')->unsigned()->index('sat_structure_organization_custom_id_foreign');
			$table->text('description', 65535)->nullable();
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
		Schema::drop('setting_approval_timesheet_item');
	}

}
