<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftScheduleChangeEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_schedule_change_employees', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('shift_schedule_change_id');
			$table->integer('user_id');
            $table->timestamps();
            
            $table->foreign('shift_schedule_change_id')->references('id')->on('shift_schedule_changes')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('shift_schedule_change_employees', function (Blueprint $table) {
            $table->dropForeign(['shift_schedule_change_id', 'user_id']);
        });
        Schema::dropIfExists('shift_schedule_change_employees');
    }
}
