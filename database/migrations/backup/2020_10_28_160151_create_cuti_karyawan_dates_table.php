<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCutiKaryawanDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuti_karyawan_dates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cuti_karyawan_id');
            $table->date('tanggal_cuti');
            $table->tinyInteger('type')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->foreign('cuti_karyawan_id')->references('id')->on('cuti_karyawan')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuti_karyawan_dates');
    }
}
