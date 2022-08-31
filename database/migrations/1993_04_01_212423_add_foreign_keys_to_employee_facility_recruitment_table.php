<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEmployeeFacilityRecruitmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('employee_facility_recruitment', function(Blueprint $table)
		{
			$table->foreign('external_application_id')->references('id')->on('external_applications')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('employee_facility_recruitment', function(Blueprint $table)
		{
			$table->dropForeign('employee_facility_recruitment_external_application_id_foreign');
		});
	}

}
