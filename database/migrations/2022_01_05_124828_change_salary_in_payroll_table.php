<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSalaryInPayrollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll', function (Blueprint $table) {
            $table->bigInteger('basic_salary')->nullable()->default(0)->change();
            $table->bigInteger('salary')->nullable()->default(0)->change();
            $table->bigInteger('call_allow')->nullable()->default(0)->change();
            $table->bigInteger('bonus')->nullable()->default(0)->change();
            $table->bigInteger('thr')->change();
            $table->bigInteger('gross_income')->nullable()->default(0)->change();
            $table->bigInteger('burden_allow')->nullable()->default(0)->change();
            $table->bigInteger('total_deduction')->nullable()->default(0)->change();
            $table->bigInteger('net_yearly_income')->nullable()->default(0)->change();
            $table->bigInteger('untaxable_income')->nullable()->default(0)->change();
            $table->bigInteger('taxable_yearly_income')->nullable()->default(0)->change();
            $table->bigInteger('income_tax_calculation_5')->nullable()->default(0)->change();
            $table->bigInteger('income_tax_calculation_15')->nullable()->default(0)->change();
            $table->bigInteger('income_tax_calculation_25')->nullable()->default(0)->change();
            $table->bigInteger('income_tax_calculation_30')->nullable()->default(0)->change();
            $table->bigInteger('yearly_income_tax')->nullable()->default(0)->change();
            $table->bigInteger('monthly_income_tax')->nullable()->default(0)->change();
            $table->bigInteger('less')->nullable()->default(0)->change();
            $table->bigInteger('thp')->nullable()->default(0)->change();
            $table->bigInteger('transport_allowance')->nullable()->default(0)->change();
            $table->bigInteger('homebase_allowance')->nullable()->default(0)->change();
            $table->bigInteger('laptop_allowance')->nullable()->default(0)->change();
            $table->bigInteger('ot_normal_hours')->nullable()->default(0)->change();
            $table->bigInteger('ot_multiple_hours')->nullable()->default(0)->change();
            $table->bigInteger('other_income')->nullable()->default(0)->change();
            $table->bigInteger('medical_claim')->nullable()->default(0)->change();
            $table->bigInteger('other_deduction')->nullable()->default(0)->change();
            $table->bigInteger('gross_income_per_month')->default(0)->change();
            $table->bigInteger('overtime_claim')->nullable()->default(0)->change();
            $table->bigInteger('overtime')->nullable()->change();
            $table->bigInteger('bpjs_ketenagakerjaan')->nullable()->default(0)->change();
            $table->bigInteger('bpjs_kesehatan')->nullable()->default(0)->change();
            $table->bigInteger('bpjs_pensiun')->nullable()->default(0)->change();
            $table->bigInteger('bpjs_ketenagakerjaan2')->nullable()->default(0)->change();
            $table->bigInteger('bpjs_kesehatan2')->nullable()->default(0)->change();
            $table->bigInteger('bpjs_pensiun2')->nullable()->default(0)->change();
            $table->bigInteger('pph21')->nullable()->change();
            $table->bigInteger('total_earnings')->nullable()->change();
            $table->bigInteger('bpjs_ketenagakerjaan_company')->nullable()->change();
            $table->bigInteger('bpjs_kesehatan_company')->nullable()->change();
            $table->bigInteger('bpjs_pensiun_company')->nullable()->change();
            $table->bigInteger('bpjs_ketenagakerjaan_employee')->nullable()->change();
            $table->bigInteger('bpjs_kesehatan_employee')->nullable()->change();
            $table->bigInteger('bpjs_pensiun_employee')->nullable()->change();
            $table->bigInteger('bpjs_jkk_company')->nullable()->change();
            $table->bigInteger('bpjs_jkm_company')->nullable()->change();
            $table->bigInteger('bpjs_jht_company')->nullable()->change();
            $table->bigInteger('bpjs_jaminan_jht_employee')->nullable()->change();
            $table->bigInteger('bpjs_jaminan_jp_employee')->nullable()->change();
            $table->bigInteger('bpjstotalearning')->nullable()->change();
            $table->bigInteger('umr_value')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll', function (Blueprint $table) {
            //
        });
    }
}
