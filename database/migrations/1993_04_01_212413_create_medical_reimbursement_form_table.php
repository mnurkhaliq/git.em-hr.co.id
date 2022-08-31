<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalReimbursementFormTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('medical_reimbursement_form', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('medical_reimbursement_id')->nullable();
			$table->date('tanggal_kwitansi')->nullable();
			$table->string('jenis_klaim');
			$table->text('keterangan', 65535)->nullable();
			$table->integer('jumlah');
			$table->timestamps();
			$table->integer('user_family_id')->nullable();
			$table->text('file_bukti_transaksi', 65535)->nullable();
			$table->integer('nominal_approve')->nullable();
			$table->integer('medical_type_id');
			$table->string('no_kwitansi');
			$table->integer('kuota_plafond');
			$table->integer('plafond_terpakai');
			$table->integer('plafond_sisa');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('medical_reimbursement_form');
	}

}
