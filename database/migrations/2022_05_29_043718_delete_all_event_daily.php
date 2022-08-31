<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteAllEventDaily extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP EVENT IF EXISTS `Event Career Daily`;");
        DB::unprepared("DROP EVENT IF EXISTS `Event Collective Leave Daily`;");
        DB::unprepared("DROP EVENT IF EXISTS `Event PTKP Daily`;");
        DB::unprepared("DROP EVENT IF EXISTS `Event Payroll Daily`;");
        DB::unprepared("DROP EVENT IF EXISTS `Event Recruitment Daily`;");
        DB::unprepared("DROP EVENT IF EXISTS `Event Shift Daily`;");
        DB::unprepared("DROP EVENT IF EXISTS `Event Annual Leave Daily`;");
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
