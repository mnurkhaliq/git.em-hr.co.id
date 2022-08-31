<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToHistoryApprovalTimesheetNoteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('history_approval_timesheet_note', function(Blueprint $table)
		{
			$table->foreign('history_approval_timesheet_id', 'satn_history_approval_timesheet_foreign')->references('id')->on('history_approval_timesheet')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('timesheet_transaction_id', 'satn_timesheet_transaction_id_foreign')->references('id')->on('timesheet_transactions')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('history_approval_timesheet_note', function(Blueprint $table)
		{
			$table->dropForeign('satn_history_approval_timesheet_foreign');
			$table->dropForeign('satn_timesheet_transaction_id_foreign');
		});
	}

}
