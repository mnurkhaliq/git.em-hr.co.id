<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRecruitmentApplicationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('recruitment_applications', function(Blueprint $table)
		{
			$table->foreign('application_status')->references('id')->on('recruitment_application_status')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('current_phase_id')->references('id')->on('recruitment_phases')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('recruitment_request_id')->references('id')->on('recruitment_request')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('recruitment_applications', function(Blueprint $table)
		{
			$table->dropForeign('recruitment_applications_application_status_foreign');
			$table->dropForeign('recruitment_applications_current_phase_id_foreign');
			$table->dropForeign('recruitment_applications_recruitment_request_id_foreign');
		});
	}

}
