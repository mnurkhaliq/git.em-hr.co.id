<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanPlafondTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_plafond', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('organisasi_position_id')->unique()->nullable();
            $table->tinyInteger('type')->nullable();
            $table->double('plafond')->nullable();
            $table->timestamps();

            $table->foreign('organisasi_position_id', 'lp_organisasi_position_id_foreign')->references('id')->on('organisasi_position')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_plafond');
    }
}
