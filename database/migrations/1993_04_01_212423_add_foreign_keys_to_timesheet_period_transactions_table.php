<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTimesheetPeriodTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('timesheet_period_transactions', function(Blueprint $table)
		{
			$table->foreign('approval_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('timesheet_activity_id')->references('id')->on('timesheet_activities')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('timesheet_category_id')->references('id')->on('timesheet_categories')->onUpdate('CASCADE')->onDelete('CASCADE');
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
		Schema::table('timesheet_period_transactions', function(Blueprint $table)
		{
			$table->dropForeign('timesheet_period_transactions_approval_id_foreign');
			$table->dropForeign('timesheet_period_transactions_timesheet_activity_id_foreign');
			$table->dropForeign('timesheet_period_transactions_timesheet_category_id_foreign');
			$table->dropForeign('timesheet_period_transactions_timesheet_period_id_foreign');
		});
	}

}
