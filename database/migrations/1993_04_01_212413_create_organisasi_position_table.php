<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganisasiPositionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('organisasi_position', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('organisasi_division_id')->nullable();
			$table->integer('organisasi_department_id')->nullable();
			$table->integer('organisasi_unit_id')->nullable();
			$table->string('name')->nullable();
			$table->timestamps();
			$table->integer('user_created')->nullable();
			$table->string('code');
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
		Schema::drop('organisasi_position');
	}

}
