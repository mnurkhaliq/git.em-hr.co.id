<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnSettingApprovalLeaveIdInSettingApprovalMedicalItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting_approval_medical_item', function (Blueprint $table) {
            //
            $table->renameColumn('setting_approval_leave_id','setting_approval_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting_approval_medical_item', function (Blueprint $table) {
            //
            $table->renameColumn('setting_approval_id','setting_approval_leave_id');
        });
    }
}
