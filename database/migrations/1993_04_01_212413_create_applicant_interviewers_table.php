<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantInterviewersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('applicant_interviewers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('recruitment_application_id')->unsigned()->index('applicant_interviewers_recruitment_application_id_foreign');
			$table->integer('user_id')->unsigned()->nullable();
			$table->timestamps();
			$table->unique(['user_id','recruitment_application_id'], 'ai_unique_ref');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('applicant_interviewers');
	}

}
