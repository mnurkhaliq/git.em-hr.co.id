<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryApprovalLoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_approval_loan', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('loan_id');
			$table->unsignedInteger('structure_organization_custom_id');
			$table->unsignedInteger('setting_approval_level_id');
			$table->integer('approval_id')->nullable();
			$table->tinyInteger('is_approved')->nullable();
			$table->dateTime('date_approved')->nullable();
			$table->text('note', 65535)->nullable();
			$table->timestamps();

            $table->foreign('loan_id')->references('id')->on('loan')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('structure_organization_custom_id', 'hal_structure_organization_custom_id_foreign')->references('id')->on('structure_organization_custom')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('setting_approval_level_id', 'hal_setting_approval_level_id_foreign')->references('id')->on('setting_approval_level')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('approval_id', 'hal_approval_id_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_approval_loan');
    }
}
