<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalReimbursementTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('medical_reimbursement', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->date('tanggal_pengajuan')->nullable();
			$table->integer('status')->nullable();
			$table->timestamps();
			$table->integer('is_approved_atasan')->nullable();
			$table->dateTime('date_approved_atasan')->nullable();
			$table->integer('approved_atasan_id')->nullable();
			$table->integer('is_approved_hr_benefit')->nullable();
			$table->integer('is_approved_manager_hr')->nullable();
			$table->integer('is_approved_gm_hr')->nullable();
			$table->smallInteger('approve_direktur')->nullable();
			$table->integer('approve_direktur_id')->nullable();
			$table->dateTime('approve_direktur_date')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('medical_reimbursement');
	}

}
