<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExternalApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('recruitment_application_id');
            $table->unsignedInteger('jobseeker_id');
            $table->dateTime('psychotest_test_schedule')->nullable();
            $table->string('psychotest_test_result')->nullable();
            $table->string('psychotest_test_remark')->nullable();
            $table->dateTime('technical_test_schedule')->nullable();
            $table->string('technical_test_result')->nullable();
            $table->string('technical_test_remark')->nullable();
            $table->dateTime('interview_test_schedule')->nullable();
            $table->string('interview_test_location')->nullable();
            $table->string('interview_test_result')->nullable();
            $table->string('interview_test_remark')->nullable();
            $table->string('reference_user_1')->nullable();
            $table->string('reference_company_1')->nullable();
            $table->string('reference_user_2')->nullable();
            $table->string('reference_company_2')->nullable();
            $table->string('reference_remark')->nullable();
            $table->dateTime('medical_test_schedule')->nullable();
            $table->string('medical_test_location')->nullable();
            $table->string('medical_test_result')->nullable();
            $table->string('medical_test_remark')->nullable();
            $table->string('offering_letter_number')->nullable();
            $table->date('offering_letter_date')->nullable();
            $table->date('offering_letter_signing_date')->nullable();
            $table->date('onboard_date')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->foreign('recruitment_application_id')->references('id')->on('recruitment_applications')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('jobseeker_id')->references('id')->on('jobseekers')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('external_applications');
    }
}
