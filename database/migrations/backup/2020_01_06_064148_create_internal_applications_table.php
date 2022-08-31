<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInternalApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('recruitment_application_id');
            $table->integer('user_id');
            $table->string('cv')->nullable();
            $table->dateTime('technical_test_schedule')->nullable();
            $table->string('technical_test_result')->nullable();
            $table->string('technical_test_remark')->nullable();
            $table->dateTime('interview_test_schedule')->nullable();
            $table->string('interview_test_location')->nullable();
            $table->string('interview_test_result')->nullable();
            $table->string('interview_test_remark')->nullable();
            $table->string('memo_number')->nullable();
            $table->date('memo_date')->nullable();
            $table->date('onboard_date')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->foreign('recruitment_application_id')->references('id')->on('recruitment_applications')->onDelete('cascade')->onUpdate('cascade');
//            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('internal_applications');
    }
}
