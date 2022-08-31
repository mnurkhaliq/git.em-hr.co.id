<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingApprovalTimesheetItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_approval_timesheet_item', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('setting_approval_id');
            $table->unsignedInteger('setting_approval_level_id');
            $table->unsignedInteger('structure_organization_custom_id');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('setting_approval_id')->references('id')->on('setting_approval')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('setting_approval_level_id', 'sat_setting_approval_level_id_foreign')->references('id')->on('setting_approval_level')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('structure_organization_custom_id', 'sat_structure_organization_custom_id_foreign')->references('id')->on('structure_organization_custom')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_approval_timesheet_item');
    }
}
