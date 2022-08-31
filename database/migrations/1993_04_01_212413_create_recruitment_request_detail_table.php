<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentRequestDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recruitment_request_detail', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('recruitment_type_id')->unsigned()->nullable();
			$table->integer('recruitment_request_id')->unsigned()->nullable()->index('recruitment_request_detail_recruitment_request_id_foreign');
			$table->dateTime('posting_date')->nullable();
			$table->date('expired_date')->nullable();
			$table->date('last_posted_date')->nullable();
			$table->boolean('status_post')->nullable();
			$table->boolean('show_salary_range')->default(0);
			$table->timestamps();
			$table->unique(['recruitment_type_id','recruitment_request_id'], 'rr_detail_unique_ref');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('recruitment_request_detail');
	}

}
