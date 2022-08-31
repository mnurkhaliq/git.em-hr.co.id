<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingApprovalTimesheetTransactionItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_approval_timesheet_transaction_item', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('timesheet_category_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamps();

            $table->foreign('timesheet_category_id', 'satt_timesheet_categories_foreign')->references('id')->on('timesheet_categories')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_approval_timesheet_transaction_item');
    }
}
