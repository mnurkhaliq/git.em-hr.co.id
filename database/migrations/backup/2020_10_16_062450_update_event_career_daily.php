<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventCareerDaily extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP EVENT IF EXISTS `Event Career Daily`; CREATE DEFINER=`".config('database.connections.mysql.username')."`@`".config('database.connections.mysql.host')."` EVENT IF NOT EXISTS `Event Career Daily` ON SCHEDULE EVERY 1 DAY STARTS '2020-09-05 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
UPDATE users u INNER JOIN career_history c ON c.user_id = u.id SET u.cabang_id = c.cabang_id, u.structure_organization_custom_id = c.structure_organization_custom_id, u.organisasi_status = c.status, u.start_date_contract = c.start_date, u.end_date_contract = c.end_date WHERE c.id IN (SELECT MAX(id) FROM career_history WHERE effective_date = CAST(NOW() AS date) GROUP BY user_id);
END");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP EVENT IF EXISTS `Event Career Daily`;");
    }
}
