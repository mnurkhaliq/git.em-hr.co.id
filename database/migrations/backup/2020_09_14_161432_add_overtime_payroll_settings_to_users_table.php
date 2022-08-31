<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOvertimePayrollSettingsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('overtime_entitle')->nullable();
            $table->integer('overtime_payroll_id')->unsigned()->nullable()->index('overtime_payroll_id')->after('overtime_entitle');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('overtime_entitle');
            $table->dropColumn('overtime_payroll_id');
        });
    }
}
