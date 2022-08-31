<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveAttendanceFromCutiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::delete("DELETE history_approval_leave, cuti_karyawan FROM history_approval_leave LEFT JOIN cuti_karyawan ON history_approval_leave.cuti_karyawan_id = cuti_karyawan.id LEFT JOIN cuti ON cuti_karyawan.jenis_cuti = cuti.id WHERE cuti.jenis_cuti = 'Attendance'");

        \DB::delete("DELETE user_cuti, cuti FROM user_cuti LEFT JOIN cuti ON user_cuti.cuti_id = cuti.id WHERE cuti.jenis_cuti = 'Attendance'");

        \DB::delete("DELETE FROM cuti WHERE cuti.jenis_cuti = 'Attendance'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
