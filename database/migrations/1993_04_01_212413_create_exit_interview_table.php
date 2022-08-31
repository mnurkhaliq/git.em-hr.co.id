<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExitInterviewTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('exit_interview', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->date('resign_date')->nullable();
			$table->timestamps();
			$table->integer('status')->nullable();
			$table->boolean('status_clearance')->default(0);
			$table->integer('exit_interview_reason')->nullable();
			$table->text('hal_berkesan', 65535)->nullable();
			$table->text('hal_tidak_berkesan', 65535)->nullable();
			$table->text('masukan', 65535)->nullable();
			$table->text('kegiatan_setelah_resign', 65535)->nullable();
			$table->text('tujuan_perusahaan_baru', 65535)->nullable();
			$table->text('jenis_bidang_usaha', 65535)->nullable();
			$table->text('other_reason', 65535)->nullable();
			$table->integer('is_approved_atasan')->nullable();
			$table->integer('approved_atasan_id')->nullable();
			$table->dateTime('date_approved_atasan')->nullable();
			$table->integer('is_approved_hrd')->nullable();
			$table->integer('is_approved_ga')->nullable();
			$table->integer('is_approved_it')->nullable();
			$table->string('inventory_it_username_pc')->nullable();
			$table->string('inventory_it_password_pc')->nullable();
			$table->string('inventory_it_email')->nullable();
			$table->string('inventory_it_username_arium')->nullable();
			$table->string('inventory_it_password_arium')->nullable();
			$table->text('note_pembatalan', 65535)->nullable();
			$table->text('noted_atasan', 65535)->nullable();
			$table->smallInteger('approve_direktur')->nullable();
			$table->integer('approve_direktur_id')->nullable();
			$table->dateTime('approve_direktur_date')->nullable();
			$table->date('last_work_date')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('exit_interview');
	}

}
