<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKeyToPayrollCycleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_cycle', function (Blueprint $table) {
            $table->string('key_name')->nullable()->after('id');
        });
        DB::statement("UPDATE payroll_cycle SET key_name = 'attendance'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_cycle', function (Blueprint $table) {
            $table->dropColumn('key_name');
        });
    }
}
