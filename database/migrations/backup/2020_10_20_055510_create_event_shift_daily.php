<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventShiftDaily extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE DEFINER=`".config('database.connections.mysql.username')."`@`".config('database.connections.mysql.host')."` EVENT IF NOT EXISTS `Event Shift Daily` ON SCHEDULE EVERY 1 DAY STARTS '2020-09-05 00:00:00' ENDS '2021-07-31 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
UPDATE `shift_schedule_change_employees` a INNER JOIN `shift_schedule_changes` b ON a.shift_schedule_change_id = b.id INNER JOIN `users` c ON a.user_id = c.id SET c.shift_id = b.shift_id WHERE b.change_date = CURDATE();
END");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP EVENT IF EXISTS `Event Shift Daily`;");
    }
}
