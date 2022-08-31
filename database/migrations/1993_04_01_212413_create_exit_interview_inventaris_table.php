<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExitInterviewInventarisTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('exit_interview_inventaris', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_inventaris_id')->nullable();
			$table->smallInteger('status')->nullable();
			$table->timestamps();
			$table->integer('exit_interview_id')->nullable();
			$table->text('catatan', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('exit_interview_inventaris');
	}

}
