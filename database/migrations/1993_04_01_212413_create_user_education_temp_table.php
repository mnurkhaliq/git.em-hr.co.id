<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEducationTempTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_education_temp', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_temp_id')->nullable();
			$table->string('pendidikan')->nullable();
			$table->integer('tahun_awal')->nullable();
			$table->integer('tahun_akhir')->nullable();
			$table->string('fakultas')->nullable();
			$table->string('jurusan')->nullable();
			$table->string('nilai')->nullable();
			$table->string('kota')->nullable();
			$table->timestamps();
			$table->string('certificate')->nullable();
			$table->text('note', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_education_temp');
	}

}
