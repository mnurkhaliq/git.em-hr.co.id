<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryApprovalTimesheetNoteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('history_approval_timesheet_note', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('history_approval_timesheet_id')->unsigned()->index('satn_history_approval_timesheet_foreign');
			$table->integer('timesheet_transaction_id')->unsigned()->index('satn_timesheet_transaction_id_foreign');
			$table->boolean('is_approved')->nullable();
			$table->text('note', 65535)->nullable();
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
		Schema::drop('history_approval_timesheet_note');
	}

}
