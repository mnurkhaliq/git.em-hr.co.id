<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEventPayrollDaily5 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP EVENT IF EXISTS `Event Payroll Daily`; CREATE DEFINER=`".config('database.connections.mysql.username')."`@`".config('database.connections.mysql.host')."` EVENT IF NOT EXISTS `Event Payroll Daily` ON SCHEDULE EVERY 1 DAY STARTS '2020-04-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN

UPDATE payroll_history ph
INNER JOIN users u ON ph.user_id = u.id
INNER JOIN setting s ON s.project_id = u.project_id
INNER JOIN payroll_cycle pc ON pc.project_id = u.project_id AND IF(u.payroll_cycle_id IS NULL, pc.key_name = 'payroll', pc.id = u.payroll_cycle_id)
LEFT JOIN loan_payment lp ON lp.payroll_history_id = ph.id
SET ph.is_lock = 1, lp.status = 5
WHERE STR_TO_DATE(CONCAT(YEAR(ph.created_at), '/', MONTH(ph.created_at), '/', pc.end_date), '%Y/%m/%d') <= DATE_SUB(NOW(), INTERVAL 1 MONTH)
AND s.key = 'schedule_lock'
AND s.value = 1;

INSERT INTO ".config('database.connections.mysql.database').".event_log (`database`, `type`, `user_id`, `transaction_id`, `description`, `date`)
SELECT Database(), 'Payroll', u.id, ph.id, 'Success Payroll', Now()
FROM payroll_history ph
INNER JOIN users u ON ph.user_id = u.id
INNER JOIN setting s ON s.project_id = u.project_id
INNER JOIN payroll_cycle pc ON pc.project_id = u.project_id AND IF(u.payroll_cycle_id IS NULL, pc.key_name = 'payroll', pc.id = u.payroll_cycle_id)
WHERE STR_TO_DATE(CONCAT(YEAR(ph.created_at), '/', MONTH(ph.created_at), '/', pc.end_date), '%Y/%m/%d') <= DATE_SUB(NOW(), INTERVAL 1 MONTH)
AND s.key = 'schedule_lock'
AND s.value = 1;

END");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP EVENT IF EXISTS `Event Payroll Daily`;");
    }
}
