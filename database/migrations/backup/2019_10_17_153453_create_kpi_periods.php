<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKpiPeriods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_periods', function (Blueprint $table) {
            $table->increments('id');
            $table->date("start_date");
            $table->date("end_date");
            $table->smallInteger("min_rate");
            $table->smallInteger("max_rate");
            $table->integer("project_id")->nullable();
            $table->tinyInteger("status");
            $table->tinyInteger("is_lock");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kpi_periods');
    }
}
