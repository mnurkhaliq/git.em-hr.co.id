<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventRecruitmentDaily extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP EVENT IF EXISTS `Event Recruitment Daily`;

CREATE DEFINER=`".config('database.connections.mysql.username')."`@`".config('database.connections.mysql.host')."` EVENT IF NOT EXISTS `Event Recruitment Daily` ON SCHEDULE EVERY 1 DAY STARTS '2020-04-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN

INSERT INTO ".config('database.connections.mysql.database').".event_log (`database`, `type`, `transaction_id`, `description`, `date`) SELECT Database(), 'Recruitment', subs.id, 'Success Recruitment', Now() FROM (SELECT * FROM recruitment_request_detail WHERE expired_date = CAST(NOW() AS date) AND status_post = 1) AS subs;

UPDATE recruitment_request_detail SET status_post = 0 WHERE expired_date = CAST(NOW() AS date) AND status_post = 1;
        
END");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP EVENT IF EXISTS `Event Recruitment Daily`;");
    }
}
