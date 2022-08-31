<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToApplicantInterviewersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('applicant_interviewers', function(Blueprint $table)
		{
			$table->foreign('recruitment_application_id')->references('id')->on('recruitment_applications')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('applicant_interviewers', function(Blueprint $table)
		{
			$table->dropForeign('applicant_interviewers_recruitment_application_id_foreign');
		});
	}

}
