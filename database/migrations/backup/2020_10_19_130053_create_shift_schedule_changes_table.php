<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftScheduleChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_schedule_changes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shift_id');
			$table->date('change_date')->nullable();
            $table->timestamps();
            
			$table->foreign('shift_id')->references('id')->on('shift')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shift_schedule_changes', function (Blueprint $table) {
            $table->dropForeign(['shift_id']);
        });
        Schema::dropIfExists('shift_schedule_changes');
    }
}
