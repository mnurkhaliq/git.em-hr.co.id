<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDateShiftToAbsensiItemTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('absensi_item_temp', function (Blueprint $table) {
            $table->date("date_out")->nullable()->after('date');
            $table->date("date_shift")->nullable()->after('date_out');
            //
        });
        DB::statement("UPDATE absensi_item_temp SET date_shift = date, date_out = date");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('absensi_item_temp', function (Blueprint $table) {
            //
        });
    }
}
