<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimesheetPeriodTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('timesheet_period_transactions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('timesheet_period_id')->unsigned()->nullable()->index('timesheet_period_transactions_timesheet_period_id_foreign');
			$table->integer('timesheet_category_id')->unsigned()->nullable()->index('timesheet_period_transactions_timesheet_category_id_foreign');
			$table->string('timesheet_category_name', 191)->nullable();
			$table->integer('timesheet_activity_id')->unsigned()->nullable()->index('timesheet_period_transactions_timesheet_activity_id_foreign');
			$table->string('timesheet_activity_name', 191)->nullable();
			$table->date('date')->nullable();
			$table->string('start_time', 191)->nullable();
			$table->string('end_time', 191)->nullable();
			$table->string('total_time', 191)->nullable();
			$table->string('duration', 191)->nullable();
			$table->text('description', 65535)->nullable();
			$table->boolean('status')->nullable();
			$table->integer('approval_id')->nullable()->index('timesheet_period_transactions_approval_id_foreign');
			$table->text('approval_note', 65535)->nullable();
			$table->dateTime('date_approved')->nullable();
			$table->text('admin_note', 65535)->nullable();
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
		Schema::drop('timesheet_period_transactions');
	}

}
