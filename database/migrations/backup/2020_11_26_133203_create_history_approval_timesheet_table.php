<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryApprovalTimesheetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_approval_timesheet', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('timesheet_period_id');
            $table->unsignedInteger('structure_organization_custom_id');
            $table->unsignedInteger('setting_approval_level_id');
            $table->integer('approval_id')->nullable();
            $table->tinyInteger('is_approved')->nullable();
            $table->dateTime('date_approved')->nullable();
            $table->timestamps();

            $table->foreign('timesheet_period_id')->references('id')->on('timesheet_periods')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('structure_organization_custom_id', 'hat_structure_organization_custom_id_foreign')->references('id')->on('structure_organization_custom')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('setting_approval_level_id', 'hat_setting_approval_level_id_id_foreign')->references('id')->on('setting_approval_level')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('approval_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_approval_timesheet');
    }
}
