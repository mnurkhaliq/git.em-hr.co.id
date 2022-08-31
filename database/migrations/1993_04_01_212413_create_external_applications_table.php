<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalApplicationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('external_applications', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('recruitment_application_id')->unsigned()->index('external_applications_recruitment_application_id_foreign');
			$table->integer('jobseeker_id')->unsigned()->index('external_applications_jobseeker_id_foreign');
			$table->dateTime('psychotest_test_schedule')->nullable();
			$table->string('psychotest_test_result', 191)->nullable();
			$table->string('psychotest_test_remark', 191)->nullable();
			$table->dateTime('technical_test_schedule')->nullable();
			$table->string('technical_test_result', 191)->nullable();
			$table->string('technical_test_remark', 191)->nullable();
			$table->dateTime('interview_test_schedule')->nullable();
			$table->string('interview_test_location', 191)->nullable();
			$table->string('interview_test_result', 191)->nullable();
			$table->string('interview_test_remark', 191)->nullable();
			$table->string('reference_user_1', 191)->nullable();
			$table->string('reference_company_1', 191)->nullable();
			$table->string('reference_user_2', 191)->nullable();
			$table->string('reference_company_2', 191)->nullable();
			$table->string('reference_remark', 191)->nullable();
			$table->dateTime('medical_test_schedule')->nullable();
			$table->string('medical_test_location', 191)->nullable();
			$table->string('medical_test_result', 191)->nullable();
			$table->string('medical_test_remark', 191)->nullable();
			$table->string('offering_letter_number', 191)->nullable();
			$table->date('offering_letter_date')->nullable();
			$table->date('offering_letter_signing_date')->nullable();
			$table->string('employment_agreement_number', 191)->nullable();
			$table->date('employment_agreement_date')->nullable();
			$table->date('employment_agreement_signing_date')->nullable();
			$table->date('onboard_date')->nullable();
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
		Schema::drop('external_applications');
	}

}
