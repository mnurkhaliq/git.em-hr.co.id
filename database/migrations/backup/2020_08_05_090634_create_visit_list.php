<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_list', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->unsignedInteger('cabang_id')->nullable();
            $table->timestamp('visit_time')->useCurrent();
            $table->string('timezone')->nullable();
            $table->string('timetable')->nullable();
            $table->boolean('isoutbranch')->nullable();
            $table->string('justification')->nullable();
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->string('locationname')->nullable();
            $table->boolean('isotheractivityname')->nullable();
            $table->string('activityname')->nullable();
            $table->string('description')->nullable();
            $table->double('radius_visit')->nullable();
            $table->double('branchlongitude')->nullable();
            $table->double('branchlatitude')->nullable();
            $table->boolean('isotherpic')->nullable();
            $table->string('picname')->nullable();
            $table->string('signature')->nullable();
            $table->timestamp('created_at');
            //$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('cabang_id')->references('id')->on('cabang')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visit_list');
    }
}
