<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExitClearanceInventoryGaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('exit_clearance_inventory_ga', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->text('name', 65535)->nullable();
			$table->string('check_form_branch')->nullable();
			$table->string('check_by_hr')->nullable();
			$table->text('keterangan', 65535)->nullable();
			$table->timestamps();
			$table->integer('exit_interview_id');
			$table->string('keterangan_dept')->nullable();
			$table->string('keterangan_hr')->nullable();
			$table->dateTime('ga_check_date')->nullable();
			$table->integer('ga_checked')->nullable();
			$table->text('ga_note', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('exit_clearance_inventory_ga');
	}

}
