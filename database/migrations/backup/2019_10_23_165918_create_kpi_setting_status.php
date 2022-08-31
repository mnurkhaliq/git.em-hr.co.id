<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKpiSettingStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_setting_status', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('kpi_setting_scoring_id')->nullable();
            $table->unsignedInteger('structure_organization_custom_id')->nullable();
            $table->tinyInteger("status")->default(0);
            $table->timestamps();
            $table->foreign('kpi_setting_scoring_id')->references('id')->on('kpi_setting_scoring')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kpi_setting_status');
    }
}
