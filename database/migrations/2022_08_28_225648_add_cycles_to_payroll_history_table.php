<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCyclesToPayrollHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_history', function (Blueprint $table) {
            $table->string('payroll_cycle_start')->nullable();
            $table->string('payroll_cycle_end')->nullable();
            $table->string('payroll_cycle_label')->nullable();
            $table->string('attendance_cycle_start')->nullable();
            $table->string('attendance_cycle_end')->nullable();
            $table->string('attendance_cycle_label')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_history', function (Blueprint $table) {
            //
        });
    }
}
