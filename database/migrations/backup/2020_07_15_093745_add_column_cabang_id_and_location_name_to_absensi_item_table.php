<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCabangIdAndLocationNameToAbsensiItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('absensi_item', function (Blueprint $table) {
            //
            $table->integer('cabang_id_in')->nullable();
            $table->integer('cabang_id_out')->nullable();
            $table->text('location_name_in')->nullable();
            $table->text('location_name_out')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('absensi_item', function (Blueprint $table) {
            //
        });
    }
}
