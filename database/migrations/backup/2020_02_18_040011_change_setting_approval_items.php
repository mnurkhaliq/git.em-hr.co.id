<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSettingApprovalItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("delete sa from setting_approval_leave_item sa left join structure_organization_custom so on sa.structure_organization_custom_id = so.id where so.id is null");
        Schema::table('setting_approval_leave_item', function (Blueprint $table) {
            $table->unsignedInteger('structure_organization_custom_id')->change();
            $table->foreign('structure_organization_custom_id','structure_leave_ref')->references('id')->on('structure_organization_custom')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement("delete sa from setting_approval_exit_item sa left join structure_organization_custom so on sa.structure_organization_custom_id = so.id where so.id is null");
        Schema::table('setting_approval_exit_item', function (Blueprint $table) {
            $table->unsignedInteger('structure_organization_custom_id')->change();
            $table->foreign('structure_organization_custom_id','structure_exit_ref')->references('id')->on('structure_organization_custom')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement("delete sa from setting_approval_medical_item sa left join structure_organization_custom so on sa.structure_organization_custom_id = so.id where so.id is null");
        Schema::table('setting_approval_medical_item', function (Blueprint $table) {
            $table->unsignedInteger('structure_organization_custom_id')->change();
            $table->foreign('structure_organization_custom_id','structure_medical_ref')->references('id')->on('structure_organization_custom')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement("delete sa from setting_approval_overtime_item sa left join structure_organization_custom so on sa.structure_organization_custom_id = so.id where so.id is null");
        Schema::table('setting_approval_overtime_item', function (Blueprint $table) {
            $table->unsignedInteger('structure_organization_custom_id')->change();
            $table->foreign('structure_organization_custom_id','structure_overtime_ref')->references('id')->on('structure_organization_custom')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement("delete sa from setting_approval_paymentrequest_item sa left join structure_organization_custom so on sa.structure_organization_custom_id = so.id where so.id is null");
        Schema::table('setting_approval_paymentrequest_item', function (Blueprint $table) {
            $table->unsignedInteger('structure_organization_custom_id')->change();
            $table->foreign('structure_organization_custom_id','structure_paymentrequest_ref')->references('id')->on('structure_organization_custom')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement("delete sa from setting_approval_recruitment_item sa left join structure_organization_custom so on sa.structure_organization_custom_id = so.id where so.id is null");
        Schema::table('setting_approval_recruitment_item', function (Blueprint $table) {
            $table->unsignedInteger('structure_organization_custom_id')->change();
            $table->foreign('structure_organization_custom_id','structure_recruitment_ref')->references('id')->on('structure_organization_custom')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement("delete sa from setting_approval_training_item sa left join structure_organization_custom so on sa.structure_organization_custom_id = so.id where so.id is null");
        Schema::table('setting_approval_training_item', function (Blueprint $table) {
            $table->unsignedInteger('structure_organization_custom_id')->change();
            $table->foreign('structure_organization_custom_id','structure_training_ref')->references('id')->on('structure_organization_custom')->onDelete('cascade')->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
