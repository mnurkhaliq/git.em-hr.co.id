<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimeSheetFormTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('overtime_sheet_form', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('overtime_sheet_id')->nullable();
			$table->date('tanggal')->nullable();
			$table->text('description', 65535)->nullable();
			$table->string('awal', 10)->nullable();
			$table->string('akhir', 10)->nullable();
			$table->string('total_lembur')->nullable();
			$table->integer('employee_id')->nullable();
			$table->integer('spv')->nullable();
			$table->integer('manager')->nullable();
			$table->timestamps();
			$table->string('total_approval')->nullable();
			$table->string('total_meal')->nullable();
			$table->string('overtime_calculate', 10)->nullable();
			$table->string('awal_claim', 10)->nullable();
			$table->string('akhir_claim', 10)->nullable();
			$table->string('total_lembur_claim')->nullable();
			$table->string('awal_approved', 10)->nullable();
			$table->string('akhir_approved', 10)->nullable();
			$table->string('total_lembur_approved')->nullable();
			$table->string('pre_awal_approved', 10)->nullable();
			$table->string('pre_akhir_approved', 10)->nullable();
			$table->string('pre_total_approved')->nullable();
			$table->integer('overtime_payroll_type_id')->unsigned()->nullable()->index('overtime_payroll_type_id');
			$table->integer('meal_allowance')->nullable();
			$table->integer('payroll_calculate')->nullable();
			$table->dateTime('claim_approval')->nullable();
			$table->dateTime('cutoff')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('overtime_sheet_form');
	}

}
