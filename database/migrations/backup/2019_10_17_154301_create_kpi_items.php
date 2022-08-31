<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKpiItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('kpi_setting_scoring_id')->nullable();
            $table->unsignedInteger('structure_organization_custom_id')->nullable();
            $table->string("name");
            $table->smallInteger("weightage");
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
        Schema::dropIfExists('kpi_items');
    }
}
