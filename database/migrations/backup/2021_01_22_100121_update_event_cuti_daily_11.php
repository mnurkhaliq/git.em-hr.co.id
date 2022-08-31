<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventCutiDaily11 extends Migration
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
