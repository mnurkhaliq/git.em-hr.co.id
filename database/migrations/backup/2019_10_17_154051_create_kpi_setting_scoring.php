<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKpiSettingScoring extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_setting_scoring', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("kpi_period_id")->nullable();
            $table->unsignedInteger("kpi_module_id")->nullable();
            $table->smallInteger("weightage");
            $table->timestamps();
            $table->foreign('kpi_period_id')->references('id')->on('kpi_periods')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kpi_setting_scoring');
    }
}
