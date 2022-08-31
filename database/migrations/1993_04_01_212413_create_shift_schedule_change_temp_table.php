<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftScheduleChangeTempTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shift_schedule_change_temp', function(Blueprint $table)
		{
			$table->increments('id');
			$table->date('change_date')->nullable();
			$table->integer('shift_id')->unsigned()->nullable()->index('shift_schedule_change_temp_shift_id_foreign');
			$table->string('shift_name', 191)->nullable();
			$table->integer('user_id')->nullable()->index('shift_schedule_change_temp_user_id_foreign');
			$table->string('user_nik', 191)->nullable();
			$table->string('user_name', 191)->nullable();
			$table->boolean('status')->nullable()->default(1);
			$table->text('description', 65535)->nullable();
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
		Schema::drop('shift_schedule_change_temp');
	}

}
