<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventShiftDaily3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP EVENT IF EXISTS `Event Shift Daily`; CREATE DEFINER=`".config('database.connections.mysql.username')."`@`".config('database.connections.mysql.host')."` EVENT IF NOT EXISTS `Event Shift Daily` ON SCHEDULE EVERY 1 DAY STARTS '2020-04-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN

UPDATE `shift_schedule_change_employees` a INNER JOIN `shift_schedule_changes` b ON a.shift_schedule_change_id = b.id INNER JOIN `users` c ON a.user_id = c.id SET c.shift_id = b.shift_id WHERE b.change_date = CURDATE();

INSERT INTO ".config('database.connections.mysql.database').".event_log (`database`, `type`, `user_id`, `transaction_id`, `description`, `date`) SELECT Database(), 'Shift', c.id, b.id, 'Success Shift', Now() FROM `shift_schedule_change_employees` a INNER JOIN `shift_schedule_changes` b ON a.shift_schedule_change_id = b.id INNER JOIN `users` c ON a.user_id = c.id WHERE b.change_date = CURDATE();

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
