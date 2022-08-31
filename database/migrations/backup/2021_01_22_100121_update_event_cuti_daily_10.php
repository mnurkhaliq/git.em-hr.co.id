<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventCutiDaily10 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP EVENT IF EXISTS `Event Cuti Daily`;

CREATE DEFINER=`".config('database.connections.mysql.username')."`@`".config('database.connections.mysql.host')."` EVENT IF NOT EXISTS `Event Cuti Daily` ON SCHEDULE EVERY 1 DAY STARTS '2020-09-05 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN

UPDATE user_cuti uc JOIN (SELECT * FROM (SELECT a.id, a.user_id, a.cuti_terpakai, a.sisa_cuti, ckd.tanggal_cuti FROM user_cuti a LEFT JOIN cuti c ON a.cuti_id = c.id LEFT JOIN users u ON a.user_id = u.id AND u.join_date <= now() AND (u.resign_date IS NULL OR u.resign_date >= now()) LEFT JOIN cuti_karyawan ck ON ck.user_id = u.id LEFT JOIN cuti_karyawan_dates ckd ON ckd.cuti_karyawan_id = ck.id AND ckd.tanggal_cuti = Cast(Now() AS date) LEFT JOIN shift s ON u.shift_id = s.id LEFT JOIN shift_detail sd ON sd.shift_id = s.id LEFT JOIN cuti_bersama z ON z.dari_tanggal = Cast(Now() AS date) WHERE c.jenis_cuti IS NOT NULL AND c.jenis_cuti = 'Annual Leave' AND (s.id IS NULL OR (s.is_collective = 0 AND sd.day IS NOT NULL AND sd.day = Dayname(Now()))) AND EXISTS (SELECT * FROM cuti_bersama x WHERE x.impacttoleave = 1 AND Day(Now()) = Day(x.dari_tanggal) AND Month(Now()) = Month(x.dari_tanggal) AND Year(Now()) = Year(x.dari_tanggal)) ORDER BY ckd.tanggal_cuti DESC LIMIT 18446744073709551615) AS sub GROUP BY sub.user_id) AS subs ON uc.id = subs.id SET uc.sisa_cuti = ( uc.sisa_cuti - 1 ), uc.cuti_terpakai = ( uc.cuti_terpakai + 1 ) WHERE subs.tanggal_cuti IS NULL;

INSERT INTO `cuti_karyawan` (`user_id`, `jenis_cuti`, `tanggal_cuti_start`, `tanggal_cuti_end`, `keperluan`, `backup_user_id`, `status`, `created_at`, `updated_at`, `total_cuti`, `temp_kuota`, `temp_cuti_terpakai`, `temp_sisa_cuti`) SELECT subs.user_id, subs.cuti_id, Cast(Now() AS date), Cast(Now() AS date), subs.description, subs.user_id, 2, Now(), Now(), 1, subs.kuota, subs.cuti_terpakai, subs.sisa_cuti FROM (SELECT * FROM (SELECT a.user_id, a.cuti_id, z.description, a.kuota, a.cuti_terpakai, a.sisa_cuti, ckd.tanggal_cuti FROM user_cuti a LEFT JOIN cuti c ON a.cuti_id = c.id LEFT JOIN users u ON a.user_id = u.id AND u.join_date <= now() AND (u.resign_date IS NULL OR u.resign_date >= now()) LEFT JOIN cuti_karyawan ck ON ck.user_id = u.id LEFT JOIN cuti_karyawan_dates ckd ON ckd.cuti_karyawan_id = ck.id AND ckd.tanggal_cuti = Cast(Now() AS date) LEFT JOIN shift s ON u.shift_id = s.id LEFT JOIN shift_detail sd ON sd.shift_id = s.id LEFT JOIN cuti_bersama z ON z.dari_tanggal = Cast(Now() AS date) WHERE c.jenis_cuti IS NOT NULL AND c.jenis_cuti = 'Annual Leave' AND (s.id IS NULL OR (s.is_collective = 0 AND sd.day IS NOT NULL AND sd.day = Dayname(Now()))) AND EXISTS (SELECT * FROM cuti_bersama x WHERE x.impacttoleave = 1 AND Day(Now()) = Day(x.dari_tanggal) AND Month(Now()) = Month(x.dari_tanggal) AND Year(Now()) = Year(x.dari_tanggal)) ORDER BY ckd.tanggal_cuti DESC LIMIT 18446744073709551615) AS sub GROUP BY sub.user_id) AS subs WHERE subs.tanggal_cuti IS NULL;

INSERT INTO `cuti_karyawan_dates` (`cuti_karyawan_id`, `tanggal_cuti`, `type`, `description`, `created_at`, `updated_at`) SELECT a.id, Cast(Now() AS date), 1, 'Leave/permit day', Now(), Now() FROM cuti_karyawan a LEFT JOIN cuti_karyawan_dates b ON a.id = b.cuti_karyawan_id WHERE a.tanggal_cuti_start = Cast(Now() AS date) AND a.tanggal_cuti_end = Cast(Now() AS date) AND b.id IS NULL;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) SELECT subs.id, 'Success Collective Leave', Now() FROM (SELECT * FROM (SELECT a.id, a.user_id, ckd.tanggal_cuti FROM user_cuti a LEFT JOIN cuti c ON a.cuti_id = c.id LEFT JOIN users u ON a.user_id = u.id AND u.join_date <= now() AND (u.resign_date IS NULL OR u.resign_date >= now()) LEFT JOIN cuti_karyawan ck ON ck.user_id = u.id LEFT JOIN cuti_karyawan_dates ckd ON ckd.cuti_karyawan_id = ck.id AND ckd.tanggal_cuti = Cast(Now() AS date) LEFT JOIN shift s ON u.shift_id = s.id LEFT JOIN shift_detail sd ON sd.shift_id = s.id LEFT JOIN cuti_bersama z ON z.dari_tanggal = Cast(Now() AS date) WHERE c.jenis_cuti IS NOT NULL AND c.jenis_cuti = 'Annual Leave' AND (s.id IS NULL OR (s.is_collective = 0 AND sd.day IS NOT NULL AND sd.day = Dayname(Now()))) AND EXISTS (SELECT * FROM cuti_bersama x WHERE x.impacttoleave = 1 AND Day(Now()) = Day(x.dari_tanggal) AND Month(Now()) = Month(x.dari_tanggal) AND Year(Now()) = Year(x.dari_tanggal)) ORDER BY ckd.tanggal_cuti DESC LIMIT 18446744073709551615) AS sub GROUP BY sub.user_id) AS subs WHERE subs.tanggal_cuti IS NULL;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.sisa_cuti<c.carryforwardleave,a.sisa_cuti,c.carryforwardleave), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=IF(a.sisa_cuti<c.carryforwardleave,IF(a.sisa_cuti>0,a.sisa_cuti,0),IF(c.carryforwardleave>0,c.carryforwardleave,0))
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=4 and DAY(now()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(now())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=1 and c.cutoffmonth is not null;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Monthly Cutoff Carry Forward', now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=4 and DAY(now()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(now())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=1 and c.cutoffmonth is not null;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.sisa_cuti<0,a.sisa_cuti,0), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=0
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=4 and DAY(now()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(now())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=0 and c.cutoffmonth is not null;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Monthly Cutoff Not Carry Forward', now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=4 and DAY(now()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(now())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=0 and c.cutoffmonth is not null;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=a.sisa_cuti+c.Kuota, a.kuota=a.kuota+c.Kuota
where b.join_date <= now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=4 and DAY(now()) ='1' and c.cutoffmonth is not null;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Monthly', now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date <= now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=4 and DAY(now()) ='1' and c.cutoffmonth is not null;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=a.sisa_cuti+c.Kuota, a.kuota=a.kuota+c.Kuota
where DATE(b.join_date) = DATE(now()) and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=4 and c.cutoffmonth is not null;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Monthly', now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where DATE(b.join_date) = DATE(now()) and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=4 and c.cutoffmonth is not null;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=c.Kuota+IF(a.sisa_cuti<c.carryforwardleave,a.sisa_cuti,c.carryforwardleave), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=c.Kuota+IF(a.sisa_cuti<c.carryforwardleave,IF(a.sisa_cuti>0,a.sisa_cuti,0),IF(c.carryforwardleave>0,c.carryforwardleave,0))
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=2 and DAY(b.join_date) = DAY(now()) and month(b.join_date) = month(now()) and timestampdiff(year, b.join_date, NOW()) >= 1 and  c.iscarryforward=1;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Anniversary Carry Forward', now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=2 and DAY(b.join_date) = DAY(now()) and month(b.join_date) = month(now()) and timestampdiff(year, b.join_date, NOW()) >= 1 and  c.iscarryforward=1;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=c.Kuota+if(a.sisa_cuti<0,a.sisa_cuti,0),a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0),a.updated_at=now(),a.kuota=c.Kuota
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=2 and DAY(b.join_date) = DAY(now()) and month(b.join_date) = month(now()) and timestampdiff(year, b.join_date, NOW()) >= 1 and  c.iscarryforward=0;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Anniversary Not Carry Forward', now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=2 and DAY(b.join_date) = DAY(now()) and month(b.join_date) = month(now()) and timestampdiff(year, b.join_date, NOW()) >= 1 and  c.iscarryforward=0;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.kuota=0, ceil((timestampdiff(month,b.join_date,NOW())+IF(DAY(b.join_date) ='1',0,1))/12*c.Kuota), c.Kuota)+IF(a.sisa_cuti<c.carryforwardleave,a.sisa_cuti,c.carryforwardleave), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=IF(a.kuota=0, ceil((timestampdiff(month,b.join_date,NOW())+IF(DAY(b.join_date) ='1',0,1))/12*c.Kuota), c.Kuota)+IF(a.sisa_cuti<c.carryforwardleave,IF(a.sisa_cuti>0,a.sisa_cuti,0),IF(c.carryforwardleave>0,c.carryforwardleave,0))
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=1 and DAY(now()) ='1' and month(now())='1' and c.iscarryforward=1;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Annually Carry Forward', now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=1 and DAY(now()) ='1' and month(now())='1' and c.iscarryforward=1;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.kuota=0, ceil((timestampdiff(month,b.join_date,NOW())+IF(DAY(b.join_date) ='1',0,1))/12*c.Kuota), c.Kuota)+if(a.sisa_cuti<0,a.sisa_cuti,0), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=IF(a.kuota=0, ceil((timestampdiff(month,b.join_date,NOW())+IF(DAY(b.join_date) ='1',0,1))/12*c.Kuota), c.Kuota)
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=1 and DAY(now()) ='1' and month(now())='1' and c.iscarryforward=0;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Annually Not Carry Forward', now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=1 and DAY(now()) ='1' and month(now())='1' and c.iscarryforward=0;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.kuota=0, ceil((timestampdiff(month,b.join_date,NOW())+IF(DAY(b.join_date) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\"),0,1))/12*c.Kuota), c.Kuota)+IF(a.sisa_cuti<c.carryforwardleave,a.sisa_cuti,c.carryforwardleave), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=IF(a.kuota=0, ceil((timestampdiff(month,b.join_date,NOW())+IF(DAY(b.join_date) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\"),0,1))/12*c.Kuota), c.Kuota)+IF(a.sisa_cuti<c.carryforwardleave,IF(a.sisa_cuti>0,a.sisa_cuti,0),IF(c.carryforwardleave>0,c.carryforwardleave,0))
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=5 and DAY(now()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(now())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=1 and c.cutoffmonth is not null;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Custom Carry Forward', now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=5 and DAY(now()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(now())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=1 and c.cutoffmonth is not null;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.kuota=0, ceil((timestampdiff(month,b.join_date,NOW())+IF(DAY(b.join_date) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\"),0,1))/12*c.Kuota), c.Kuota)+if(a.sisa_cuti<0,a.sisa_cuti,0), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=IF(a.kuota=0, ceil((timestampdiff(month,b.join_date,NOW())+IF(DAY(b.join_date) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\"),0,1))/12*c.Kuota), c.Kuota)
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=5 and DAY(now()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(now())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=0 and c.cutoffmonth is not null;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Custom Not Carry Forward', now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=5 and DAY(now()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(now())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=0 and c.cutoffmonth is not null;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=c.Kuota+if(a.sisa_cuti<0,a.sisa_cuti,0),a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0),a.updated_at=now(),a.kuota=c.Kuota
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=3 and DAY(b.join_date) = DAY(now()) and month(b.join_date) = month(now()) and timestampdiff(year,b.join_date,NOW())=1;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Anniversary Anniversary Annually', now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=3 and DAY(b.join_date) = DAY(now()) and month(b.join_date) = month(now()) and timestampdiff(year,b.join_date,NOW())=1;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(timestampdiff(year,b.join_date,NOW())<2, ceil((timestampdiff(month,b.join_date,NOW())-IF(DAY(b.join_date) ='1',12,11))/12*c.Kuota), c.Kuota)+IF(a.sisa_cuti<c.carryforwardleave,a.sisa_cuti,c.carryforwardleave), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=IF(timestampdiff(year,b.join_date,NOW())<2, ceil((timestampdiff(month,b.join_date,NOW())-IF(DAY(b.join_date) ='1',12,11))/12*c.Kuota), c.Kuota)+IF(a.sisa_cuti<c.carryforwardleave,IF(a.sisa_cuti>0,a.sisa_cuti,0),IF(c.carryforwardleave>0,c.carryforwardleave,0))
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=3 and DAY(now()) ='1' and month(now())='1' and DATE_ADD(b.join_date, INTERVAL 1 YEAR) < NOW() and c.iscarryforward=1;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Annually Anniversary Annually Carry Forward', now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=3 and DAY(now()) ='1' and month(now())='1' and DATE_ADD(b.join_date, INTERVAL 1 YEAR) < NOW() and c.iscarryforward=1;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(timestampdiff(year,b.join_date,NOW())<2, ceil((timestampdiff(month,b.join_date,NOW())-IF(DAY(b.join_date) ='1',12,11))/12*c.Kuota), c.Kuota)+if(a.sisa_cuti<0,a.sisa_cuti,0), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=IF(timestampdiff(year,b.join_date,NOW())<2, ceil((timestampdiff(month,b.join_date,NOW())-IF(DAY(b.join_date) ='1',12,11))/12*c.Kuota), c.Kuota)
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=3 and DAY(now()) ='1' and month(now())='1' and DATE_ADD(b.join_date, INTERVAL 1 YEAR) < NOW() and c.iscarryforward=0;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Annually Anniversary Annually Not Carry Forward', now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < now() and (b.resign_date IS NULL or b.resign_date > now()) and c.master_cuti_type_id=3 and DAY(now()) ='1' and month(now())='1' and DATE_ADD(b.join_date, INTERVAL 1 YEAR) < NOW() and c.iscarryforward=0;

END");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP EVENT IF EXISTS `Event Cuti Daily`;");
    }
}
