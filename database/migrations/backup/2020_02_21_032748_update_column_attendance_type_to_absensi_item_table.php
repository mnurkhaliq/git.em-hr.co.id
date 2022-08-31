<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnAttendanceTypeToAbsensiItemTable extends Migration
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
            $table->renameColumn('attendance_type','attendance_type_in');
            $table->string("attendance_type_out")->default("normal");
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
