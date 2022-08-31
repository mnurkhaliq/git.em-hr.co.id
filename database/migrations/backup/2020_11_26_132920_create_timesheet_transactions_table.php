<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimesheetTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheet_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('timesheet_period_id')->nullable();
            $table->unsignedInteger('timesheet_activity_id')->nullable();
            $table->date('date')->nullable();
            $table->double('duration')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();
            
            $table->foreign('timesheet_period_id')->references('id')->on('timesheet_periods')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('timesheet_activity_id')->references('id')->on('timesheet_activities')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timesheet_transactions');
    }
}
