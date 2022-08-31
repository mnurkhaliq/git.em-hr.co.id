<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalApplicationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('internal_applications', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('recruitment_application_id')->unsigned()->index('internal_applications_recruitment_application_id_foreign');
			$table->integer('user_id');
			$table->string('cv', 191)->nullable();
			$table->dateTime('technical_test_schedule')->nullable();
			$table->string('technical_test_result', 191)->nullable();
			$table->string('technical_test_remark', 191)->nullable();
			$table->dateTime('interview_test_schedule')->nullable();
			$table->string('interview_test_location', 191)->nullable();
			$table->string('interview_test_result', 191)->nullable();
			$table->string('interview_test_remark', 191)->nullable();
			$table->string('memo_number', 191)->nullable();
			$table->date('memo_date')->nullable();
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
		Schema::drop('internal_applications');
	}

}
