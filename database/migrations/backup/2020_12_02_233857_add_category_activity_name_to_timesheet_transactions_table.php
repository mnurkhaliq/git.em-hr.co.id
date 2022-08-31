<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryActivityNameToTimesheetTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timesheet_transactions', function (Blueprint $table) {
            $table->unsignedInteger('timesheet_category_id')->nullable()->after('timesheet_period_id');
            $table->string('timesheet_category_name')->nullable()->after('timesheet_category_id');
            $table->string('timesheet_activity_name')->nullable()->after('timesheet_activity_id');

            $table->foreign('timesheet_category_id')->references('id')->on('timesheet_categories')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('timesheet_transactions', function (Blueprint $table) {
            //
        });
    }
}
