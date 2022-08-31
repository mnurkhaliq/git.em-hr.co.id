<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExitInterviewAssetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('exit_interview_assets', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('exit_interview_id')->nullable();
			$table->integer('asset_id')->nullable();
			$table->smallInteger('status')->nullable();
			$table->text('catatan', 65535)->nullable();
			$table->text('catatan_user', 65535)->nullable();
			$table->string('asset_condition', 191)->nullable();
			$table->timestamps();
			$table->integer('user_check')->nullable();
			$table->integer('approval_check')->nullable();
			$table->integer('approval_id')->nullable();
			$table->dateTime('date_approved')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('exit_interview_assets');
	}

}
