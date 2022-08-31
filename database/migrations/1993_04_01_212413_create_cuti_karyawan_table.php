<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCutiKaryawanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cuti_karyawan', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->string('jenis_cuti')->nullable();
			$table->date('tanggal_cuti_start')->nullable();
			$table->date('tanggal_cuti_end')->nullable();
			$table->text('keperluan', 65535)->nullable();
			$table->integer('backup_user_id')->nullable();
			$table->text('catatan_atasan', 65535)->nullable();
			$table->text('catatan_personalia', 65535)->nullable();
			$table->integer('status')->nullable();
			$table->timestamps();
			$table->integer('is_approved_atasan')->nullable();
			$table->dateTime('date_approved_atasan')->nullable();
			$table->integer('approved_atasan_id')->nullable();
			$table->integer('is_approved_personalia')->nullable();
			$table->integer('is_personalia_id')->nullable();
			$table->time('jam_datang_terlambat')->nullable();
			$table->time('jam_pulang_cepat')->nullable();
			$table->string('note_pembatalan')->nullable();
			$table->integer('total_cuti')->nullable();
			$table->integer('approve_direktur_id')->nullable();
			$table->integer('approve_direktur')->nullable();
			$table->dateTime('approve_direktur_date')->nullable();
			$table->smallInteger('temp_kuota')->nullable();
			$table->smallInteger('temp_cuti_terpakai')->nullable();
			$table->smallInteger('temp_sisa_cuti')->nullable();
			$table->text('approve_direktur_noted', 65535)->nullable();
			$table->string('attachment', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cuti_karyawan');
	}

}
