<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimesheetActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheet_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('timesheet_category_id')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();

            $table->foreign('timesheet_category_id')->references('id')->on('timesheet_categories')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timesheet_activities');
    }
}
