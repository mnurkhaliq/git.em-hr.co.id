<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScoresToKpiEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kpi_employee', function (Blueprint $table) {
            //
            $table->float('manager_score')->after('employee_feedback')->nullable();
            $table->float('organization_score')->after('employee_feedback')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kpi_employee', function (Blueprint $table) {
            //
            $table->dropColumn('manager_score');
            $table->dropColumn('organization_score');
        });
    }
}
