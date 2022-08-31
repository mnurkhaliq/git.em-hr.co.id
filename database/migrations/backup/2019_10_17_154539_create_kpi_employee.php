<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKpiEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_employee', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("user_id")->nullable();
            $table->unsignedInteger("structure_organization_custom_id")->nullable();
            $table->unsignedInteger("kpi_period_id")->nullable();
            $table->unsignedInteger("supervisor_id")->nullable();
            $table->date("employee_input_date")->nullable();
            $table->date("supervisor_input_date")->nullable();
            $table->string("employee_feedback")->nullable();
            $table->float("final_score")->nullable();
            $table->tinyInteger("status")->default(0);
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
        Schema::dropIfExists('kpi_employee');
    }
}
