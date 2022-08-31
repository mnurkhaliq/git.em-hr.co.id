<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensiItemTempTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('absensi_item_temp', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->integer('same_user_id')->nullable();
			$table->string('emp_no')->nullable();
			$table->string('ac_no')->nullable();
			$table->string('name')->nullable();
			$table->string('auto_assign')->nullable();
			$table->date('date')->nullable();
			$table->date('date_out')->nullable();
			$table->date('date_shift')->nullable();
			$table->string('timetable')->nullable();
			$table->string('on_dutty')->nullable();
			$table->string('off_dutty')->nullable();
			$table->string('clock_in')->nullable();
			$table->string('clock_out')->nullable();
			$table->string('normal')->nullable();
			$table->string('real_time')->nullable();
			$table->string('late')->nullable();
			$table->string('early')->nullable();
			$table->string('absent')->nullable();
			$table->string('ot_time')->nullable();
			$table->string('work_time')->nullable();
			$table->string('exception')->nullable();
			$table->string('must_c_in')->nullable();
			$table->string('must_c_out')->nullable();
			$table->string('department')->nullable();
			$table->timestamps();
			$table->integer('absensi_item_id')->nullable();
			$table->string('ndays')->nullable();
			$table->string('weekend')->nullable();
			$table->string('holiday')->nullable();
			$table->string('att_time')->nullable();
			$table->string('ndays_ot')->nullable();
			$table->string('weekend_ot')->nullable();
			$table->string('holiday_ot')->nullable();
			$table->string('no')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('absensi_item_temp');
	}

}
