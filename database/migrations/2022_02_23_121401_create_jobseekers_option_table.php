<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobseekersOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobseekers_option', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('jobseekers_id')->nullable();
            $table->unsignedInteger('bank_cv_option_id')->nullable();
            $table->unsignedInteger('bank_cv_option_value_id')->nullable();
            $table->timestamps();

            $table->foreign('jobseekers_id')->references('id')->on('jobseekers')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('bank_cv_option_id')->references('id')->on('bank_cv_option')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('bank_cv_option_value_id')->references('id')->on('bank_cv_option_value')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobseekers_option');
    }
}
