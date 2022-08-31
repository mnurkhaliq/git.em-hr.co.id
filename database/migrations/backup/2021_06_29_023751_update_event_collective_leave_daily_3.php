<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventCollectiveLeaveDaily3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP EVENT IF EXISTS `Event Collective Leave Daily`;

CREATE DEFINER=`".config('database.connections.mysql.username')."`@`".config('database.connections.mysql.host')."` EVENT IF NOT EXISTS `Event Collective Leave Daily` ON SCHEDULE EVERY 1 DAY STARTS '2020-09-05 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN

INSERT INTO ".config('database.connections.mysql.database').".event_log (`database`, `type`, `user_id`, `transaction_id`, `description`, `date`) SELECT Database(), 'Collective Leave', subs.user_id, subs.id, 'Success Collective Leave', Now() FROM (SELECT * FROM (SELECT a.id, a.user_id, ckd.tanggal_cuti FROM user_cuti a LEFT JOIN cuti c ON a.cuti_id = c.id LEFT JOIN users u ON a.user_id = u.id AND u.join_date <= now() AND (u.resign_date IS NULL OR u.resign_date >= now()) LEFT JOIN cuti_karyawan ck ON ck.user_id = u.id AND ck.status IN (1, 2, 6, 8) LEFT JOIN cuti_karyawan_dates ckd ON ckd.cuti_karyawan_id = ck.id AND ckd.tanggal_cuti = Cast(Now() AS date) LEFT JOIN shift s ON u.shift_id = s.id LEFT JOIN shift_detail sd ON sd.shift_id = s.id LEFT JOIN cuti_bersama z ON z.dari_tanggal = Cast(Now() AS date) WHERE c.jenis_cuti IS NOT NULL AND c.jenis_cuti = 'Annual Leave' AND (s.id IS NULL OR (s.is_collective = 0 AND sd.day IS NOT NULL AND sd.day = Dayname(Now()))) AND EXISTS (SELECT * FROM cuti_bersama x WHERE x.impacttoleave = 1 AND Day(Now()) = Day(x.dari_tanggal) AND Month(Now()) = Month(x.dari_tanggal) AND Year(Now()) = Year(x.dari_tanggal)) ORDER BY ckd.tanggal_cuti DESC LIMIT 18446744073709551615) AS sub GROUP BY sub.user_id) AS subs WHERE subs.tanggal_cuti IS NULL;

UPDATE user_cuti uc JOIN (SELECT * FROM (SELECT a.id, a.user_id, a.cuti_terpakai, a.sisa_cuti, ckd.tanggal_cuti FROM user_cuti a LEFT JOIN cuti c ON a.cuti_id = c.id LEFT JOIN users u ON a.user_id = u.id AND u.join_date <= now() AND (u.resign_date IS NULL OR u.resign_date >= now()) LEFT JOIN cuti_karyawan ck ON ck.user_id = u.id AND ck.status IN (1, 2, 6, 8) LEFT JOIN cuti_karyawan_dates ckd ON ckd.cuti_karyawan_id = ck.id AND ckd.tanggal_cuti = Cast(Now() AS date) LEFT JOIN shift s ON u.shift_id = s.id LEFT JOIN shift_detail sd ON sd.shift_id = s.id LEFT JOIN cuti_bersama z ON z.dari_tanggal = Cast(Now() AS date) WHERE c.jenis_cuti IS NOT NULL AND c.jenis_cuti = 'Annual Leave' AND (s.id IS NULL OR (s.is_collective = 0 AND sd.day IS NOT NULL AND sd.day = Dayname(Now()))) AND EXISTS (SELECT * FROM cuti_bersama x WHERE x.impacttoleave = 1 AND Day(Now()) = Day(x.dari_tanggal) AND Month(Now()) = Month(x.dari_tanggal) AND Year(Now()) = Year(x.dari_tanggal)) ORDER BY ckd.tanggal_cuti DESC LIMIT 18446744073709551615) AS sub GROUP BY sub.user_id) AS subs ON uc.id = subs.id SET uc.sisa_cuti = ( uc.sisa_cuti - 1 ), uc.cuti_terpakai = ( uc.cuti_terpakai + 1 ) WHERE subs.tanggal_cuti IS NULL;

INSERT INTO `cuti_karyawan` (`user_id`, `jenis_cuti`, `tanggal_cuti_start`, `tanggal_cuti_end`, `keperluan`, `backup_user_id`, `status`, `created_at`, `updated_at`, `total_cuti`, `temp_kuota`, `temp_cuti_terpakai`, `temp_sisa_cuti`) SELECT subs.user_id, subs.cuti_id, Cast(Now() AS date), Cast(Now() AS date), subs.description, subs.user_id, 2, Now(), Now(), 1, subs.kuota, subs.cuti_terpakai, subs.sisa_cuti FROM (SELECT * FROM (SELECT a.user_id, a.cuti_id, z.description, a.kuota, a.cuti_terpakai, a.sisa_cuti, ckd.tanggal_cuti FROM user_cuti a LEFT JOIN cuti c ON a.cuti_id = c.id LEFT JOIN users u ON a.user_id = u.id AND u.join_date <= now() AND (u.resign_date IS NULL OR u.resign_date >= now()) LEFT JOIN cuti_karyawan ck ON ck.user_id = u.id AND ck.status IN (1, 2, 6, 8) LEFT JOIN cuti_karyawan_dates ckd ON ckd.cuti_karyawan_id = ck.id AND ckd.tanggal_cuti = Cast(Now() AS date) LEFT JOIN shift s ON u.shift_id = s.id LEFT JOIN shift_detail sd ON sd.shift_id = s.id LEFT JOIN cuti_bersama z ON z.dari_tanggal = Cast(Now() AS date) WHERE c.jenis_cuti IS NOT NULL AND c.jenis_cuti = 'Annual Leave' AND (s.id IS NULL OR (s.is_collective = 0 AND sd.day IS NOT NULL AND sd.day = Dayname(Now()))) AND EXISTS (SELECT * FROM cuti_bersama x WHERE x.impacttoleave = 1 AND Day(Now()) = Day(x.dari_tanggal) AND Month(Now()) = Month(x.dari_tanggal) AND Year(Now()) = Year(x.dari_tanggal)) ORDER BY ckd.tanggal_cuti DESC LIMIT 18446744073709551615) AS sub GROUP BY sub.user_id) AS subs WHERE subs.tanggal_cuti IS NULL;

INSERT INTO `cuti_karyawan_dates` (`cuti_karyawan_id`, `tanggal_cuti`, `type`, `description`, `created_at`, `updated_at`) SELECT a.id, Cast(Now() AS date), 1, 'Leave/permit day', Now(), Now() FROM cuti_karyawan a LEFT JOIN cuti_karyawan_dates b ON a.id = b.cuti_karyawan_id WHERE a.tanggal_cuti_start = Cast(Now() AS date) AND a.tanggal_cuti_end = Cast(Now() AS date) AND b.id IS NULL;

END");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP EVENT IF EXISTS `Event Collective Leave Daily`;");
    }
}
