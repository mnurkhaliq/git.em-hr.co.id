<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recruitment_request', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('request_number', 191)->unique('rn_unique_ref');
			$table->integer('requestor_id')->unsigned();
			$table->integer('branch_id')->unsigned();
			$table->integer('structure_organization_custom_id')->unsigned()->nullable()->index('structure_rec_req_ref');
			$table->string('job_position', 191)->nullable();
			$table->integer('recruiter_id')->unsigned()->nullable();
			$table->integer('grade_id')->unsigned()->nullable();
			$table->integer('subgrade_id')->unsigned()->nullable();
			$table->integer('job_category_id')->unsigned()->nullable()->index('recruitment_request_job_category_id_foreign');
			$table->integer('min_salary')->unsigned()->nullable();
			$table->integer('max_salary')->unsigned()->nullable();
			$table->boolean('status')->nullable();
			$table->boolean('approval_hr')->nullable();
			$table->integer('approval_hr_user_id')->unsigned()->nullable();
			$table->dateTime('approval_hr_date')->nullable();
			$table->boolean('approval_user')->nullable();
			$table->boolean('status_post')->default(0);
			$table->text('reason', 65535)->nullable();
			$table->boolean('headcount');
			$table->text('job_requirement', 65535)->nullable();
			$table->text('job_desc', 65535)->nullable();
			$table->text('benefit', 65535)->nullable();
			$table->date('expected_date')->nullable();
			$table->boolean('employment_type')->nullable();
			$table->boolean('contract_duration')->nullable();
			$table->text('additional_information', 65535)->nullable();
			$table->timestamps();
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
		Schema::drop('recruitment_request');
	}

}
