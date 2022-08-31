<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('nik', 100)->nullable();
			$table->string('tempat_lahir')->nullable();
			$table->date('tanggal_lahir')->nullable();
			$table->string('jenis_kelamin', 25)->nullable();
			$table->string('telepon')->nullable();
			$table->string('foto')->nullable();
			$table->string('foto_ktp')->nullable();
			$table->string('email', 100)->nullable();
			$table->string('password')->nullable();
			$table->text('password_reset_token', 65535)->nullable();
			$table->string('name')->nullable();
			$table->string('remember_token', 100)->nullable();
			$table->timestamps();
			$table->string('agama', 25)->nullable();
			$table->dateTime('last_logged_in_at')->nullable();
			$table->date('last_logged_in_mobile')->nullable();
			$table->dateTime('last_logged_out_at')->nullable();
			$table->integer('access_id');
			$table->integer('status')->nullable();
			$table->integer('division_id')->nullable();
			$table->string('ext')->nullable();
			$table->string('ktp_number')->nullable();
			$table->string('passport_number')->nullable();
			$table->string('kk_number')->nullable();
			$table->string('npwp_number')->nullable();
			$table->string('jamsostek_number')->nullable();
			$table->string('bpjs_number')->nullable();
			$table->string('marital_status', 25)->nullable();
			$table->integer('provinsi_id')->nullable();
			$table->integer('kabupaten_id')->nullable();
			$table->integer('kecamatan_id')->nullable();
			$table->bigInteger('kelurahan_id')->nullable();
			$table->integer('id_zip_code')->nullable();
			$table->integer('hak_cuti')->nullable();
			$table->integer('cuti_yang_terpakai')->nullable();
			$table->integer('sisa_cuti')->nullable();
			$table->integer('cabang_id')->nullable();
			$table->string('nama_rekening')->nullable();
			$table->string('nomor_rekening')->nullable();
			$table->integer('bank_id')->nullable();
			$table->dateTime('join_date')->nullable();
			$table->date('resign_date')->nullable();
			$table->date('inactive_date')->nullable();
			$table->text('current_address', 65535)->nullable();
			$table->integer('provinsi_current')->nullable();
			$table->integer('kabupaten_current')->nullable();
			$table->integer('kecamatan_current')->nullable();
			$table->integer('kelurahan_current')->nullable();
			$table->integer('current_zip_code')->nullable();
			$table->string('mobile_2', 100)->nullable();
			$table->string('emergency_name', 100)->nullable();
			$table->string('emergency_relationship', 100)->nullable();
			$table->string('emergency_contact', 100)->nullable();
			$table->string('blood_type', 10)->nullable();
			$table->text('id_address', 65535)->nullable();
			$table->string('mobile_1', 50)->nullable();
			$table->string('length_of_service', 50)->nullable();
			$table->string('cuti_status', 100)->nullable();
			$table->string('branch_type', 50)->nullable();
			$table->string('organisasi_status', 50)->nullable();
			$table->string('status_contract', 191)->nullable();
			$table->date('start_date_contract')->nullable();
			$table->date('end_date_contract')->nullable();
			$table->string('cuti_2018')->nullable();
			$table->integer('employee_number')->nullable();
			$table->string('absensi_number', 9)->nullable();
			$table->smallInteger('is_pic_cabang')->nullable();
			$table->smallInteger('is_generate_kontrak')->nullable();
			$table->text('generate_kontrak_file', 65535)->nullable();
			$table->dateTime('end_date')->nullable();
			$table->string('no_kontrak', 100)->nullable();
			$table->integer('empore_organisasi_direktur')->nullable();
			$table->integer('empore_organisasi_manager_id')->nullable();
			$table->integer('empore_organisasi_staff_id')->nullable();
			$table->integer('empore_organisasi_level')->nullable();
			$table->smallInteger('is_reset_first_password')->nullable();
			$table->dateTime('last_change_password')->nullable();
			$table->integer('structure_organization_custom_id')->nullable();
			$table->integer('project_id')->nullable();
			$table->text('apikey', 65535)->nullable();
			$table->text('firebase_token', 65535)->nullable();
			$table->string('os_type', 191)->nullable()->default('android');
			$table->string('os_version', 191)->nullable();
			$table->string('device_name', 191)->nullable();
			$table->string('app_version', 191)->nullable();
			$table->integer('absensi_setting_id')->nullable();
			$table->integer('status_active')->default(1);
			$table->integer('shift_id')->nullable();
			$table->integer('master_visit_type_id')->unsigned()->nullable()->index('users_master_visit_type_id_foreign');
			$table->integer('master_category_visit_id')->unsigned()->nullable()->index('users_master_category_visit_id_foreign');
			$table->integer('overtime_entitle')->nullable();
			$table->integer('overtime_payroll_id')->unsigned()->nullable()->index('overtime_payroll_id');
			$table->integer('payroll_country_id')->unsigned()->nullable()->index('users_payroll_country_id_foreign');
			$table->boolean('foreigners_status')->nullable();
			$table->integer('payroll_umr_id')->unsigned()->nullable()->index('users_payroll_umr_id_foreign');
			$table->string('payroll_jenis_kelamin', 191)->nullable();
			$table->string('payroll_marital_status', 191)->nullable();
			$table->integer('payroll_cycle_id')->unsigned()->nullable()->index('users_payroll_cycle_id_foreign');
			$table->integer('attendance_cycle_id')->unsigned()->nullable()->index('users_attendance_cycle_id_foreign');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
