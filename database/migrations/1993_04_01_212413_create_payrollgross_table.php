<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollgrossTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payrollgross', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('user_id')->nullable();
			$table->integer('basic_salary')->nullable()->default(0);
			$table->integer('salary')->nullable()->default(0);
			$table->integer('call_allow')->nullable()->default(0);
			$table->integer('bonus')->nullable()->default(0);
			$table->integer('gross_income')->nullable()->default(0);
			$table->integer('burden_allow')->nullable()->default(0);
			$table->integer('total_deduction')->nullable()->default(0);
			$table->integer('net_yearly_income')->nullable()->default(0);
			$table->integer('untaxable_income')->nullable()->default(0);
			$table->integer('taxable_yearly_income')->nullable()->default(0);
			$table->integer('income_tax_calculation_5')->nullable()->default(0);
			$table->integer('income_tax_calculation_15')->nullable()->default(0);
			$table->integer('income_tax_calculation_25')->nullable()->default(0);
			$table->integer('income_tax_calculation_30')->nullable()->default(0);
			$table->integer('yearly_income_tax')->nullable()->default(0);
			$table->integer('monthly_income_tax')->nullable()->default(0);
			$table->integer('less')->nullable()->default(0);
			$table->integer('thp')->nullable()->default(0);
			$table->smallInteger('is_calculate')->nullable();
			$table->integer('transport_allowance')->nullable()->default(0);
			$table->integer('homebase_allowance')->nullable()->default(0);
			$table->integer('laptop_allowance')->nullable()->default(0);
			$table->integer('ot_normal_hours')->nullable()->default(0);
			$table->integer('ot_multiple_hours')->nullable()->default(0);
			$table->integer('other_income')->nullable()->default(0);
			$table->string('remark_other_income')->nullable();
			$table->integer('medical_claim')->nullable()->default(0);
			$table->string('remark')->nullable();
			$table->integer('other_deduction')->nullable()->default(0);
			$table->string('remark_other_deduction')->nullable();
			$table->integer('gross_income_per_month')->default(0);
			$table->integer('overtime_claim')->nullable()->default(0);
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
		Schema::drop('payrollgross');
	}

}
