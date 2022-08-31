<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecruitmentRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_request', function (Blueprint $table) {
            $table->increments('id');
            $table->string('request_number');
            $table->unsignedInteger('requestor_id');
            $table->unsignedInteger('branch_id');
            $table->unsignedInteger('structure_organization_custom_id');
            $table->unsignedInteger('recruiter_id')->nullable();
            $table->unsignedInteger('grade_id')->nullable();
            $table->unsignedInteger('subgrade_id')->nullable();
            $table->unsignedInteger('min_salary')->nullable();
            $table->unsignedInteger('max_salary')->nullable();
            $table->tinyInteger('approval_hr')->nullable();
            $table->unsignedInteger('approval_hr_user_id')->nullable();
            $table->dateTime('approval_hr_date')->nullable();
            $table->tinyInteger('approval_user')->nullable();
            $table->tinyInteger('status_post')->default(0);
            $table->text('reason')->nullable();
            $table->tinyInteger('headcount');
            $table->text('job_requirement')->nullable();
            $table->text('job_desc')->nullable();
            $table->date('expected_date')->nullable();
            $table->tinyInteger('employment_type')->nullable();
            $table->tinyInteger('contract_duration')->nullable();
            $table->text('additional_information')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->unique('request_number','rn_unique_ref');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recruitment_request');
    }
}
