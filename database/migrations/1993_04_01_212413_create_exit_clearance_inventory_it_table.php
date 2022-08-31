<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExitClearanceInventoryItTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('exit_clearance_inventory_it', function(Blueprint $table)
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
			$table->smallInteger('it_checked');
			$table->dateTime('it_checked_date');
			$table->text('it_checked_note', 65535);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('exit_clearance_inventory_it');
	}

}
