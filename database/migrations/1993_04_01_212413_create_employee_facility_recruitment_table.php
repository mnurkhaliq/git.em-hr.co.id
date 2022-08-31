<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeFacilityRecruitmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employee_facility_recruitment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('external_application_id')->unsigned()->index('employee_facility_recruitment_external_application_id_foreign');
			$table->integer('asset_type_id');
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
		Schema::drop('employee_facility_recruitment');
	}

}
