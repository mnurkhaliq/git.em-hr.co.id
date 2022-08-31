<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('training', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->string('topik_kegiatan')->nullable();
			$table->date('tanggal_kegiatan_start')->nullable();
			$table->date('tanggal_kegiatan_end')->nullable();
			$table->integer('pengambilan_uang_muka')->nullable();
			$table->date('tanggal_pengajuan')->nullable();
			$table->date('tanggal_penyelesaian')->nullable();
			$table->string('tipe_perjalanan')->nullable();
			$table->string('transportasi_berangkat', 191)->nullable();
			$table->string('transportasi_pulang', 191)->nullable();
			$table->string('rute_dari_berangkat')->nullable();
			$table->string('rute_tujuan_berangkat')->nullable();
			$table->string('rute_dari_pulang', 191)->nullable();
			$table->string('rute_tujuan_pulang', 191)->nullable();
			$table->string('tipe_kelas_berangkat')->nullable();
			$table->string('tipe_kelas_pulang', 191)->nullable();
			$table->integer('transportasi_ticket')->nullable();
			$table->integer('transportasi_taxi')->nullable();
			$table->integer('transportasi_gasoline')->nullable();
			$table->integer('transportasi_tol')->nullable();
			$table->integer('transportasi_parkir')->nullable();
			$table->integer('hotel_plafond')->nullable();
			$table->integer('uang_saku_kegiatan')->nullable();
			$table->integer('uang_makan')->nullable();
			$table->timestamps();
			$table->string('lokasi_kegiatan')->nullable();
			$table->string('jenis_training')->nullable();
			$table->string('other_jenis_training')->nullable();
			$table->string('tempat_tujuan')->nullable();
			$table->string('nama_transportasi_berangkat')->nullable();
			$table->string('nama_transportasi_pulang', 191)->nullable();
			$table->string('status')->nullable();
			$table->integer('is_approved_atasan')->nullable();
			$table->dateTime('date_approved_atasan')->nullable();
			$table->integer('approved_atasan_id')->nullable();
			$table->integer('cabang_id')->nullable();
			$table->date('tanggal_berangkat')->nullable();
			$table->time('waktu_berangkat')->nullable();
			$table->date('tanggal_pulang')->nullable();
			$table->time('waktu_pulang')->nullable();
			$table->integer('transportasi_ticket_disetujui')->nullable();
			$table->text('transportasi_ticket_file', 65535)->nullable();
			$table->text('transportasi_ticket_catatan', 65535)->nullable();
			$table->integer('transportasi_taxi_disetujui')->nullable();
			$table->text('transportasi_taxi_file', 65535)->nullable();
			$table->text('transportasi_taxi_catatan', 65535)->nullable();
			$table->integer('transportasi_gasoline_disetujui')->nullable();
			$table->text('transportasi_gasoline_file', 65535)->nullable();
			$table->text('transportasi_gasoline_catatan', 65535)->nullable();
			$table->integer('transportasi_tol_disetujui')->nullable();
			$table->text('transportasi_tol_file', 65535)->nullable();
			$table->text('transportasi_tol_catatan', 65535)->nullable();
			$table->integer('transportasi_parkir_disetujui')->nullable();
			$table->text('transportasi_parkir_file', 65535)->nullable();
			$table->text('transportasi_parkir_catatan', 65535)->nullable();
			$table->integer('uang_hotel_plafond')->nullable();
			$table->integer('uang_hotel_nominal')->nullable();
			$table->integer('uang_hotel_qty')->nullable();
			$table->integer('uang_hotel_nominal_disetujui')->nullable();
			$table->text('uang_hotel_file', 65535)->nullable();
			$table->text('uang_hotel_catatan', 65535)->nullable();
			$table->integer('uang_makan_plafond')->nullable();
			$table->integer('uang_makan_nominal')->nullable();
			$table->integer('uang_makan_qty')->nullable();
			$table->integer('uang_makan_nominal_disetujui')->nullable();
			$table->text('uang_makan_file', 65535)->nullable();
			$table->text('uang_makan_catatan', 65535)->nullable();
			$table->integer('uang_harian_plafond')->nullable();
			$table->integer('uang_harian_nominal')->nullable();
			$table->integer('uang_harian_qty')->nullable();
			$table->integer('uang_harian_nominal_disetujui')->nullable();
			$table->text('uang_harian_file', 65535)->nullable();
			$table->text('uang_harian_catatan', 65535)->nullable();
			$table->integer('uang_pesawat_plafond')->nullable();
			$table->integer('uang_pesawat_nominal')->nullable();
			$table->integer('uang_pesawat_qty')->nullable();
			$table->integer('uang_pesawat_nominal_disetujui')->nullable();
			$table->text('uang_pesawat_file', 65535)->nullable();
			$table->text('uang_pesawat_catatan', 65535)->nullable();
			$table->string('uang_biaya_lainnya1')->nullable();
			$table->integer('uang_biaya_lainnya1_nominal')->nullable();
			$table->integer('uang_biaya_lainnya1_nominal_disetujui')->nullable();
			$table->text('uang_biaya_lainnya1_file', 65535)->nullable();
			$table->text('uang_biaya_lainnya1_catatan', 65535)->nullable();
			$table->string('uang_biaya_lainnya2')->nullable();
			$table->integer('uang_biaya_lainnya2_nominal')->nullable();
			$table->integer('uang_biaya_lainnya2_nominal_disetujui')->nullable();
			$table->text('uang_biaya_lainnya2_file', 65535)->nullable();
			$table->text('uang_biaya_lainnya2_catatan', 65535)->nullable();
			$table->integer('status_actual_bill')->nullable();
			$table->integer('is_approve_atasan_actual_bill')->nullable();
			$table->integer('is_approve_hrd_actual_bill')->nullable();
			$table->string('others')->nullable();
			$table->smallInteger('approved_hrd')->nullable();
			$table->integer('approved_hrd_id')->nullable();
			$table->dateTime('approved_hrd_date')->nullable();
			$table->smallInteger('approved_finance')->nullable();
			$table->integer('approved_finance_id')->nullable();
			$table->dateTime('approved_finance_date')->nullable();
			$table->text('note_pembatalan', 65535)->nullable();
			$table->integer('sub_total_1')->nullable();
			$table->integer('sub_total_2')->nullable();
			$table->integer('sub_total_3')->nullable();
			$table->integer('sub_total_1_disetujui')->nullable();
			$table->integer('sub_total_2_disetujui')->nullable();
			$table->integer('sub_total_3_disetujui')->nullable();
			$table->smallInteger('is_approve_finance_actual_bill')->nullable();
			$table->string('pergi_bersama')->nullable();
			$table->text('note', 65535)->nullable();
			$table->text('noted_bill', 65535)->nullable();
			$table->integer('approve_direktur')->nullable();
			$table->integer('approve_direktur_id')->nullable();
			$table->integer('approve_direktur_actual_bill')->nullable();
			$table->dateTime('approve_direktur_date')->nullable();
			$table->dateTime('approve_direktur_actual_bill_date')->nullable();
			$table->date('date_submit_actual_bill')->nullable();
			$table->integer('sub_total_4')->nullable();
			$table->integer('sub_total_4_disetujui')->nullable();
			$table->integer('training_type_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('training');
	}

}
