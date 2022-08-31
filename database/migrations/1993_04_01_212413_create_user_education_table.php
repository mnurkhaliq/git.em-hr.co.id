<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEducationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_education', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->string('pendidikan')->nullable();
			$table->string('tahun_awal')->nullable();
			$table->string('tahun_akhir')->nullable();
			$table->string('fakultas')->nullable();
			$table->string('jurusan')->nullable();
			$table->string('nilai')->nullable();
			$table->string('kota')->nullable();
			$table->string('certificate')->nullable();
			$table->string('note')->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_education');
	}

}
