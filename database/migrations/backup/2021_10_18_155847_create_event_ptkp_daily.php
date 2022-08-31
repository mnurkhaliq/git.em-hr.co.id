<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventPtkpDaily extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE DEFINER=`".config('database.connections.mysql.username')."`@`".config('database.connections.mysql.host')."` EVENT IF NOT EXISTS `Event PTKP Daily` ON SCHEDULE EVERY 1 DAY STARTS '2020-09-05 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN

INSERT INTO ".config('database.connections.mysql.database').".event_log (`database`, `type`, `user_id`, `description`, `date`) SELECT Database(), 'PTKP', subs.id, 'Success PTKP', Now() FROM (SELECT id FROM users WHERE (jenis_kelamin != payroll_jenis_kelamin OR marital_status != payroll_marital_status) AND DAY(NOW()) = '1' AND MONTH(NOW()) = '1') AS subs;

UPDATE users SET payroll_jenis_kelamin = jenis_kelamin, payroll_marital_status = marital_status WHERE (jenis_kelamin != payroll_jenis_kelamin OR marital_status != payroll_marital_status) AND DAY(NOW()) = '1' AND MONTH(NOW()) = '1';

END");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP EVENT IF EXISTS `Event PTKP Daily`;");
    }
}
