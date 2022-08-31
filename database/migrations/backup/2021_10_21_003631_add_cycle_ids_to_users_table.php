<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCycleIdsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('payroll_cycle_id')->nullable();
            $table->unsignedInteger('attendance_cycle_id')->nullable();

            $table->foreign('payroll_cycle_id')->references('id')->on('payroll_cycle')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign('attendance_cycle_id')->references('id')->on('payroll_cycle')->onUpdate('CASCADE')->onDelete('SET NULL');
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
            //
        });
    }
}
