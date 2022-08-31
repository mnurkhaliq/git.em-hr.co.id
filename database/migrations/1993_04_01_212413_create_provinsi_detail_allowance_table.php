<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvinsiDetailAllowanceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('provinsi_detail_allowance', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_prov')->nullable();
			$table->text('type', 65535)->nullable();
			$table->integer('project_id')->nullable();
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
		Schema::drop('provinsi_detail_allowance');
	}

}
