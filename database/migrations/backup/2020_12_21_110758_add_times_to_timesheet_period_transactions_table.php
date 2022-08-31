<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimesToTimesheetPeriodTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timesheet_period_transactions', function (Blueprint $table) {
            $table->string('start_time')->nullable()->after('date');
            $table->string('end_time')->nullable()->after('start_time');
            $table->string('total_time')->nullable()->after('end_time');
            $table->string('duration')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('timesheet_period_transactions', function (Blueprint $table) {
            //
        });
    }
}
