<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStructureOrganizationCustomEmployeeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('structure_organization_custom_employee', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('structure_organization_custom_id')->nullable();
			$table->integer('employee_id')->nullable();
			$table->string('job_desk')->nullable();
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
		Schema::drop('structure_organization_custom_employee');
	}

}
