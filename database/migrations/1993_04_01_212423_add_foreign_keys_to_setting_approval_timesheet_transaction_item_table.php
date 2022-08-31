<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSettingApprovalTimesheetTransactionItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('setting_approval_timesheet_transaction_item', function(Blueprint $table)
		{
			$table->foreign('timesheet_category_id', 'satt_timesheet_categories_foreign')->references('id')->on('timesheet_categories')->onUpdate('CASCADE')->onDelete('CASCADE');
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
		Schema::table('setting_approval_timesheet_transaction_item', function(Blueprint $table)
		{
			$table->dropForeign('satt_timesheet_categories_foreign');
			$table->dropForeign('setting_approval_timesheet_transaction_item_user_id_foreign');
		});
	}

}
