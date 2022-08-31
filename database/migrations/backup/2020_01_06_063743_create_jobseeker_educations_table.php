<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobseekerEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobseeker_educations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('jobseeker_id');
            $table->unsignedInteger('education_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('school_name');
            $table->string('major');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->foreign('jobseeker_id')->references('id')->on('jobseekers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('education_id')->references('id')->on('educations')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobseeker_educations');
    }
}
