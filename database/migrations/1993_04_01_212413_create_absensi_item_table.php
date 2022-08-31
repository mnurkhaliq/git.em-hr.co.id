<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensiItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('absensi_item', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('absensi_id');
			$table->integer('user_id')->nullable();
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
			$table->string('ndays')->nullable();
			$table->string('weekend')->nullable();
			$table->string('holiday')->nullable();
			$table->string('att_time')->nullable();
			$table->string('ndays_ot')->nullable();
			$table->string('weekend_ot')->nullable();
			$table->string('holiday_ot')->nullable();
			$table->string('no')->nullable();
			$table->integer('absensi_device_id')->nullable();
			$table->string('long')->nullable();
			$table->string('lat')->nullable();
			$table->string('pic')->nullable();
			$table->text('pic_out', 65535)->nullable();
			$table->string('long_out')->nullable();
			$table->string('lat_out')->nullable();
			$table->float('lat_office_in', 10, 0)->nullable();
			$table->float('long_office_in', 10, 0)->nullable();
			$table->integer('radius_office_in')->nullable();
			$table->integer('absensi_setting_id')->nullable();
			$table->string('timezone', 191)->nullable();
			$table->integer('shift_id')->nullable();
			$table->integer('is_holiday')->nullable();
			$table->string('attendance_type_in', 191)->default('normal');
			$table->string('attendance_type_out', 191)->default('normal');
			$table->text('justification_in', 65535)->nullable();
			$table->text('justification_out', 65535)->nullable();
			$table->float('lat_office_out', 10, 0)->nullable();
			$table->float('long_office_out', 10, 0)->nullable();
			$table->integer('radius_office_out')->nullable();
			$table->integer('cabang_id_in')->nullable();
			$table->integer('cabang_id_out')->nullable();
			$table->text('location_name_in', 65535)->nullable();
			$table->text('location_name_out', 65535)->nullable();
			$table->string('shift_type', 191)->nullable()->default('normal');
			$table->text('shift_justification', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('absensi_item');
	}

}
