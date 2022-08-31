<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsApprovedToHistoryApprovalTimesheetNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history_approval_timesheet_note', function (Blueprint $table) {
            $table->tinyInteger('is_approved')->nullable()->after('timesheet_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history_approval_timesheet_note', function (Blueprint $table) {
            //
        });
    }
}
