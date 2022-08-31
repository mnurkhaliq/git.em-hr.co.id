<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTempTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_temp', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nik')->nullable();
			$table->string('name')->nullable();
			$table->date('join_date')->nullable();
			$table->string('gender', 20)->nullable();
			$table->string('place_of_birth')->nullable();
			$table->string('date_of_birth')->nullable();
			$table->text('id_address', 65535)->nullable();
			$table->integer('provinsi_id')->nullable();
			$table->integer('kabupaten_id')->nullable();
			$table->integer('kecamatan_id')->nullable();
			$table->integer('kelurahan_id')->nullable();
			$table->integer('id_zip_code')->nullable();
			$table->string('current_address')->nullable();
			$table->integer('provinsi_current')->nullable();
			$table->integer('kabupaten_current')->nullable();
			$table->integer('kecamatan_current')->nullable();
			$table->integer('kelurahan_current')->nullable();
			$table->integer('current_zip_code')->nullable();
			$table->string('telp')->nullable();
			$table->string('mobile_1', 25)->nullable();
			$table->string('mobile_2', 25)->nullable();
			$table->string('emergency_name', 100)->nullable();
			$table->string('emergency_relationship', 100)->nullable();
			$table->string('emergency_contact', 100)->nullable();
			$table->string('email', 100)->nullable();
			$table->string('password', 191)->nullable();
			$table->string('blood_type')->nullable();
			$table->string('bank_1')->nullable();
			$table->string('bank_account_name_1')->nullable();
			$table->string('bank_account_number')->nullable();
			$table->string('organisasi_branch')->nullable();
			$table->string('organisasi_ho_or_branch')->nullable();
			$table->string('organisasi_status')->nullable();
			$table->string('status_contract', 191)->nullable();
			$table->date('start_date_contract')->nullable();
			$table->date('end_date_contract')->nullable();
			$table->string('cuti_join_date')->nullable();
			$table->string('cuti_length_of_service')->nullable();
			$table->string('cuti_status')->nullable();
			$table->string('cuti_cuti_2018')->nullable();
			$table->string('cuti_terpakai')->nullable();
			$table->string('cuti_sisa_cuti')->nullable();
			$table->timestamps();
			$table->integer('user_id')->nullable();
			$table->integer('employee_number')->nullable();
			$table->integer('absensi_number')->nullable();
			$table->integer('ext')->nullable();
			$table->string('agama', 100)->nullable();
			$table->string('ktp_number', 100)->nullable();
			$table->string('passport_number', 100)->nullable();
			$table->string('kk_number', 100)->nullable();
			$table->string('npwp_number', 100)->nullable();
			$table->string('jamsostek_number', 100)->nullable();
			$table->string('bpjs_number', 100)->nullable();
			$table->string('marital_status', 100)->nullable();
			$table->integer('empore_organisasi_direktur')->nullable();
			$table->integer('empore_organisasi_manager_id')->nullable();
			$table->integer('empore_organisasi_staff_id')->nullable();
			$table->integer('empore_organisasi_level')->nullable();
			$table->integer('absensi_setting_id')->nullable();
			$table->string('position')->nullable();
			$table->string('division')->nullable();
			$table->string('structure_id')->nullable();
			$table->integer('branch')->nullable();
			$table->integer('shift_id')->nullable();
			$table->integer('master_category_visit_id')->unsigned()->nullable();
			$table->integer('master_visit_type_id')->unsigned()->nullable();
			$table->integer('overtime_entitle')->nullable();
			$table->integer('overtime_payroll_id')->unsigned()->nullable()->index('overtime_payroll_id');
			$table->integer('payroll_country_id')->unsigned()->nullable();
			$table->boolean('foreigners_status')->nullable();
			$table->integer('payroll_umr_id')->unsigned()->nullable();
			$table->integer('payroll_cycle_id')->unsigned()->nullable();
			$table->integer('attendance_cycle_id')->unsigned()->nullable();
			$table->string('ptkp', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users_temp');
	}

}
