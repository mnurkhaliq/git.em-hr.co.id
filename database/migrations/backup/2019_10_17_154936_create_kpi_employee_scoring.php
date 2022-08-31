<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKpiEmployeeScoring extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_employee_scoring', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("kpi_employee_id")->nullable();
            $table->unsignedInteger("kpi_item_id")->nullable();
            $table->float("self_score")->nullable();
            $table->smallInteger("supervisor_score")->nullable();
            $table->string("justification")->nullable();
            $table->string("comment")->nullable();
            $table->timestamps();
            $table->foreign('kpi_employee_id')->references('id')->on('kpi_employee')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kpi_item_id')->references('id')->on('kpi_items')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kpi_employee_scoring');
    }
}
