<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOvertimesColumnToOvertimeSheetFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('overtime_sheet_form', function (Blueprint $table) {
            $table->integer('overtime_payroll_type_id')->unsigned()->nullable()->index('overtime_payroll_type_id')->after('pre_total_approved');
			$table->integer('meal_allowance')->nullable()->after('overtime_payroll_type_id');
			$table->integer('payroll_calculate')->nullable()->after('meal_allowance');
			$table->dateTime('claim_approval')->nullable()->after('payroll_calculate');
			$table->dateTime('cutoff')->nullable()->after('claim_approval');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('overtime_sheet_form', function (Blueprint $table) {
            $table->dropColumn('overtime_payroll_type_id');
            $table->dropColumn('meal_allowance');
            $table->dropColumn('payroll_calculate');
            $table->dropColumn('claim_approval');
            $table->dropColumn('cutoff');
        });
    }
}
