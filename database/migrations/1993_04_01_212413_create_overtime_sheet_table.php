<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimeSheetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('overtime_sheet', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->integer('status')->nullable();
			$table->timestamps();
			$table->integer('is_approved_atasan')->nullable();
			$table->dateTime('date_approved_atasan')->nullable();
			$table->integer('approved_atasan_id')->nullable();
			$table->smallInteger('is_payment_request')->nullable();
			$table->integer('is_hr_benefit_approved')->nullable();
			$table->integer('is_hr_manager')->nullable();
			$table->smallInteger('approve_direktur')->nullable();
			$table->integer('approve_direktur_id')->nullable();
			$table->dateTime('approve_direktur_date')->nullable();
			$table->string('total_approval_all', 50)->nullable();
			$table->string('total_meal_all', 50)->nullable();
			$table->integer('status_claim')->nullable();
			$table->dateTime('date_claim')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('overtime_sheet');
	}

}
