<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobseekerEducationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jobseeker_educations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('jobseeker_id')->unsigned()->index('jobseeker_educations_jobseeker_id_foreign');
			$table->integer('education_id')->unsigned()->index('jobseeker_educations_education_id_foreign');
			$table->date('start_date');
			$table->date('end_date');
			$table->string('school_name', 191);
			$table->string('major', 191);
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
		Schema::drop('jobseeker_educations');
	}

}
