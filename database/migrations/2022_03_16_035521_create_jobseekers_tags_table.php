<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobseekersTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobseekers_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('jobseekers_id')->nullable();
            $table->string('tag')->nullable();
            $table->timestamps();

            $table->foreign('jobseekers_id')->references('id')->on('jobseekers')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobseekers_tags');
    }
}
