<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryApprovalOvertimeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('history_approval_overtime', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('overtime_sheet_id');
			$table->integer('structure_organization_custom_id');
			$table->integer('setting_approval_level_id');
			$table->integer('approval_id')->nullable();
			$table->integer('is_approved')->nullable();
			$table->dateTime('date_approved')->nullable();
			$table->text('note', 65535)->nullable();
			$table->timestamps();
			$table->integer('approval_id_claim')->nullable();
			$table->integer('is_approved_claim')->nullable();
			$table->dateTime('date_approved_claim')->nullable();
			$table->text('note_claim', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('history_approval_overtime');
	}

}
