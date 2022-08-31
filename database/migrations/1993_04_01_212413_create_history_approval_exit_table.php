<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryApprovalExitTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('history_approval_exit', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('exit_interview_id');
			$table->integer('structure_organization_custom_id');
			$table->integer('setting_approval_level_id');
			$table->integer('approval_id')->nullable();
			$table->integer('is_approved')->nullable();
			$table->dateTime('date_approved')->nullable();
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
		Schema::drop('history_approval_exit');
	}

}
