<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftScheduleChangeTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_schedule_change_temp', function (Blueprint $table) {
            $table->increments('id');
            $table->date('change_date')->nullable();
            $table->unsignedInteger('shift_id')->nullable();
            $table->string('shift_name')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('user_nik')->nullable();
            $table->string('user_name')->nullable();
            $table->tinyInteger('status')->nullable()->default(1);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('shift_id')->references('id')->on('shift')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_schedule_change_temp');
    }
}
