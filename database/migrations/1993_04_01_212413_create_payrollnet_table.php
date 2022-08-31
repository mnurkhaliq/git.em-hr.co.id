<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollnetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payrollnet', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('user_id')->nullable();
			$table->integer('basic_salary')->nullable()->default(0);
			$table->integer('salary')->nullable()->default(0);
			$table->integer('call_allowance')->nullable()->default(0);
			$table->integer('transport_allowance')->nullable()->default(0);
			$table->integer('meal_allow')->nullable()->default(0);
			$table->integer('homebase_allowance')->nullable()->default(0);
			$table->integer('laptop_allowance')->nullable()->default(0);
			$table->integer('overtime')->nullable()->default(0);
			$table->integer('bonus')->nullable()->default(0);
			$table->integer('medical_claim')->nullable()->default(0);
			$table->string('remark_medical')->nullable();
			$table->integer('other_income')->nullable()->default(0);
			$table->string('remark_other_income')->nullable();
			$table->integer('other_income2')->nullable()->default(0);
			$table->string('remark_other_income2')->nullable();
			$table->integer('total_income')->nullable()->default(0);
			$table->integer('deduction1')->nullable()->default(0);
			$table->string('remark_deduction1')->nullable();
			$table->integer('deduction2')->nullable()->default(0);
			$table->string('remark_deduction2')->nullable();
			$table->integer('deduction3')->nullable()->default(0);
			$table->string('remark_deduction3')->nullable();
			$table->integer('total_deduction')->nullable()->default(0);
			$table->integer('thp')->nullable()->default(0);
			$table->integer('yearly_income_tax')->nullable()->default(0);
			$table->integer('monthly_income_tax')->nullable()->default(0);
			$table->integer('ot_normal_hours')->nullable()->default(0);
			$table->smallInteger('is_calculate')->nullable();
			$table->integer('bpjs_ketenagakerjaan')->nullable()->default(0);
			$table->integer('bpjs_kesehatan')->nullable()->default(0);
			$table->integer('bpjs_pensiun')->nullable()->default(0);
			$table->integer('bpjs_ketenagakerjaan2')->nullable()->default(0);
			$table->integer('bpjs_kesehatan2')->nullable()->default(0);
			$table->integer('bpjs_pensiun2')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payrollnet');
	}

}
