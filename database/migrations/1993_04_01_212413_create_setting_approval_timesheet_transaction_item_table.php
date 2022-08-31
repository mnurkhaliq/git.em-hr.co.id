<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingApprovalTimesheetTransactionItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('setting_approval_timesheet_transaction_item', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('timesheet_category_id')->unsigned()->nullable()->index('satt_timesheet_categories_foreign');
			$table->integer('user_id')->nullable()->index('setting_approval_timesheet_transaction_item_user_id_foreign');
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
		Schema::drop('setting_approval_timesheet_transaction_item');
	}

}
