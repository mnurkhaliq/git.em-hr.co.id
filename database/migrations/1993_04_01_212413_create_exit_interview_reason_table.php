<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExitInterviewReasonTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('exit_interview_reason', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('is_parent')->nullable();
			$table->string('parent_label')->nullable();
			$table->integer('parent_id')->nullable();
			$table->text('label', 65535)->nullable();
			$table->string('type')->nullable();
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
		Schema::drop('exit_interview_reason');
	}

}
