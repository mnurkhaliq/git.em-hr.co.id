<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HistoryApprovalTimesheetNote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_approval_timesheet_note', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('history_approval_timesheet_id');
            $table->unsignedInteger('timesheet_transaction_id');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('history_approval_timesheet_id', 'satn_history_approval_timesheet_foreign')->references('id')->on('history_approval_timesheet')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('timesheet_transaction_id', 'satn_timesheet_transaction_id_foreign')->references('id')->on('timesheet_transactions')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_approval_timesheet_note');
    }
}
