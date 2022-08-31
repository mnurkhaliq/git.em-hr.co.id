<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventCutiDaily12 extends Migration
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

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.sisa_cuti<c.carryforwardleave,a.sisa_cuti,c.carryforwardleave), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=NOW(), a.kuota=IF(a.sisa_cuti<c.carryforwardleave,IF(a.sisa_cuti>0,a.sisa_cuti,0),IF(c.carryforwardleave>0,c.carryforwardleave,0))
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=4 and DAY(NOW()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(NOW())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=1 and c.cutoffmonth is not null;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Monthly Cutoff Carry Forward', NOW() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=4 and DAY(NOW()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(NOW())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=1 and c.cutoffmonth is not null;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.sisa_cuti<0,a.sisa_cuti,0), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=NOW(), a.kuota=0
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=4 and DAY(NOW()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(NOW())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=0 and c.cutoffmonth is not null;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Monthly Cutoff Not Carry Forward', NOW() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=4 and DAY(NOW()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(NOW())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=0 and c.cutoffmonth is not null;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=a.sisa_cuti+c.Kuota, a.kuota=a.kuota+c.Kuota
where b.join_date <= DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=4 and DAY(NOW()) ='1' and c.cutoffmonth is not null;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Monthly', NOW() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date <= DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=4 and DAY(NOW()) ='1' and c.cutoffmonth is not null;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=a.sisa_cuti+c.Kuota, a.kuota=a.kuota+c.Kuota
where DATE(b.join_date) = DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=4 and c.cutoffmonth is not null;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Monthly', NOW() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where DATE(b.join_date) = DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=4 and c.cutoffmonth is not null;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=c.Kuota+IF(a.sisa_cuti<c.carryforwardleave,a.sisa_cuti,c.carryforwardleave), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=NOW(), a.kuota=c.Kuota+IF(a.sisa_cuti<c.carryforwardleave,IF(a.sisa_cuti>0,a.sisa_cuti,0),IF(c.carryforwardleave>0,c.carryforwardleave,0))
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=2 and DAY(b.join_date) = DAY(NOW()) and month(b.join_date) = month(NOW()) and timestampdiff(year, b.join_date, DATE(NOW())) >= 1 and  c.iscarryforward=1;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Anniversary Carry Forward', NOW() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=2 and DAY(b.join_date) = DAY(NOW()) and month(b.join_date) = month(NOW()) and timestampdiff(year, b.join_date, DATE(NOW())) >= 1 and  c.iscarryforward=1;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=c.Kuota+if(a.sisa_cuti<0,a.sisa_cuti,0),a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0),a.updated_at=NOW(),a.kuota=c.Kuota
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=2 and DAY(b.join_date) = DAY(NOW()) and month(b.join_date) = month(NOW()) and timestampdiff(year, b.join_date, DATE(NOW())) >= 1 and  c.iscarryforward=0;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Anniversary Not Carry Forward', NOW() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=2 and DAY(b.join_date) = DAY(NOW()) and month(b.join_date) = month(NOW()) and timestampdiff(year, b.join_date, DATE(NOW())) >= 1 and  c.iscarryforward=0;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.kuota=0, ceil((timestampdiff(month,b.join_date,DATE(NOW()))+IF(DAY(b.join_date) ='1',0,1))/12*c.Kuota), c.Kuota)+IF(a.sisa_cuti<c.carryforwardleave,a.sisa_cuti,c.carryforwardleave), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=NOW(), a.kuota=IF(a.kuota=0, ceil((timestampdiff(month,b.join_date,DATE(NOW()))+IF(DAY(b.join_date) ='1',0,1))/12*c.Kuota), c.Kuota)+IF(a.sisa_cuti<c.carryforwardleave,IF(a.sisa_cuti>0,a.sisa_cuti,0),IF(c.carryforwardleave>0,c.carryforwardleave,0))
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=1 and DAY(NOW()) ='1' and month(NOW())='1' and c.iscarryforward=1;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Annually Carry Forward', NOW() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=1 and DAY(NOW()) ='1' and month(NOW())='1' and c.iscarryforward=1;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.kuota=0, ceil((timestampdiff(month,b.join_date,DATE(NOW()))+IF(DAY(b.join_date) ='1',0,1))/12*c.Kuota), c.Kuota)+if(a.sisa_cuti<0,a.sisa_cuti,0), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=NOW(), a.kuota=IF(a.kuota=0, ceil((timestampdiff(month,b.join_date,DATE(NOW()))+IF(DAY(b.join_date) ='1',0,1))/12*c.Kuota), c.Kuota)
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=1 and DAY(NOW()) ='1' and month(NOW())='1' and c.iscarryforward=0;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Annually Not Carry Forward', NOW() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=1 and DAY(NOW()) ='1' and month(NOW())='1' and c.iscarryforward=0;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.kuota=0, ceil((timestampdiff(month,b.join_date,DATE(NOW()))+IF(DAY(b.join_date) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\"),0,1))/12*c.Kuota), c.Kuota)+IF(a.sisa_cuti<c.carryforwardleave,a.sisa_cuti,c.carryforwardleave), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=NOW(), a.kuota=IF(a.kuota=0, ceil((timestampdiff(month,b.join_date,DATE(NOW()))+IF(DAY(b.join_date) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\"),0,1))/12*c.Kuota), c.Kuota)+IF(a.sisa_cuti<c.carryforwardleave,IF(a.sisa_cuti>0,a.sisa_cuti,0),IF(c.carryforwardleave>0,c.carryforwardleave,0))
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=5 and DAY(NOW()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(NOW())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=1 and c.cutoffmonth is not null;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Custom Carry Forward', NOW() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=5 and DAY(NOW()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(NOW())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=1 and c.cutoffmonth is not null;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.kuota=0, ceil((timestampdiff(month,b.join_date,DATE(NOW()))+IF(DAY(b.join_date) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\"),0,1))/12*c.Kuota), c.Kuota)+if(a.sisa_cuti<0,a.sisa_cuti,0), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=NOW(), a.kuota=IF(a.kuota=0, ceil((timestampdiff(month,b.join_date,DATE(NOW()))+IF(DAY(b.join_date) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\"),0,1))/12*c.Kuota), c.Kuota)
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=5 and DAY(NOW()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(NOW())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=0 and c.cutoffmonth is not null;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Custom Not Carry Forward', NOW() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=5 and DAY(NOW()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(NOW())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=0 and c.cutoffmonth is not null;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=c.Kuota+if(a.sisa_cuti<0,a.sisa_cuti,0),a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0),a.updated_at=NOW(),a.kuota=c.Kuota
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=3 and DAY(b.join_date) = DAY(NOW()) and month(b.join_date) = month(NOW()) and timestampdiff(year,b.join_date,DATE(NOW()))=1;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Anniversary Anniversary Annually', NOW() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=3 and DAY(b.join_date) = DAY(NOW()) and month(b.join_date) = month(NOW()) and timestampdiff(year,b.join_date,DATE(NOW()))=1;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(timestampdiff(year,b.join_date,DATE(NOW()))<2, ceil((timestampdiff(month,b.join_date,DATE(NOW()))-IF(DAY(b.join_date) ='1',12,11))/12*c.Kuota), c.Kuota)+IF(a.sisa_cuti<c.carryforwardleave,a.sisa_cuti,c.carryforwardleave), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=NOW(), a.kuota=IF(timestampdiff(year,b.join_date,DATE(NOW()))<2, ceil((timestampdiff(month,b.join_date,DATE(NOW()))-IF(DAY(b.join_date) ='1',12,11))/12*c.Kuota), c.Kuota)+IF(a.sisa_cuti<c.carryforwardleave,IF(a.sisa_cuti>0,a.sisa_cuti,0),IF(c.carryforwardleave>0,c.carryforwardleave,0))
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=3 and DAY(NOW()) ='1' and month(NOW())='1' and DATE_ADD(b.join_date, INTERVAL 1 YEAR) < DATE(NOW()) and c.iscarryforward=1;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Annually Anniversary Annually Carry Forward', NOW() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=3 and DAY(NOW()) ='1' and month(NOW())='1' and DATE_ADD(b.join_date, INTERVAL 1 YEAR) < DATE(NOW()) and c.iscarryforward=1;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(timestampdiff(year,b.join_date,DATE(NOW()))<2, ceil((timestampdiff(month,b.join_date,DATE(NOW()))-IF(DAY(b.join_date) ='1',12,11))/12*c.Kuota), c.Kuota)+if(a.sisa_cuti<0,a.sisa_cuti,0), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=NOW(), a.kuota=IF(timestampdiff(year,b.join_date,DATE(NOW()))<2, ceil((timestampdiff(month,b.join_date,DATE(NOW()))-IF(DAY(b.join_date) ='1',12,11))/12*c.Kuota), c.Kuota)
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=3 and DAY(NOW()) ='1' and month(NOW())='1' and DATE_ADD(b.join_date, INTERVAL 1 YEAR) < DATE(NOW()) and c.iscarryforward=0;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id, 'Success Annually Anniversary Annually Not Carry Forward', NOW() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id
where b.join_date < DATE(NOW()) and (b.resign_date IS NULL or b.resign_date > DATE(NOW())) and c.master_cuti_type_id=3 and DAY(NOW()) ='1' and month(NOW())='1' and DATE_ADD(b.join_date, INTERVAL 1 YEAR) < DATE(NOW()) and c.iscarryforward=0;

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
