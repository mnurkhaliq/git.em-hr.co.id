<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvinsiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('provinsi', function(Blueprint $table)
		{
			$table->integer('id_prov')->primary();
			$table->text('nama');
			$table->timestamps();
			$table->string('type')->nullable();
			$table->integer('project_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('provinsi');
	}

}
