<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleBackupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('schedule_backup', function(Blueprint $table)
		{
			$table->increments('id');
			$table->smallInteger('backup_type')->nullable();
			$table->string('recurring')->nullable();
			$table->date('date')->nullable();
			$table->time('time')->nullable();
			$table->timestamps();
			$table->integer('user_created')->nullable();
			$table->integer('project_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('schedule_backup');
	}

}
