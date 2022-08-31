<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventCutiDaily extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP EVENT IF EXISTS `Event Cuti Daily`;

CREATE DEFINER=`".config('database.connections.mysql.username')."`@`".config('database.connections.mysql.host')."` EVENT IF NOT EXISTS `Event Cuti Daily` ON SCHEDULE EVERY 1 DAY STARTS '2020-09-05 00:00:00' ENDS '2021-07-31 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=c.Kuota+if(a.sisa_cuti<0,a.sisa_cuti,0),a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0),a.updated_at=now(),a.kuota=c.Kuota where timestampdiff(day,b.join_date,NOW())>=365 and c.master_cuti_type_id=2 and DAY(b.join_date) = DAY(now()) and month(b.join_date) = month(now()) and c.iscarryforward=0;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id ,'Success Anniversary' ,now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id where timestampdiff(day,b.join_date,NOW())>=365 and c.master_cuti_type_id=2 and DAY(b.join_date) = DAY(now()) and month(b.join_date) = month(now()) and c.iscarryforward=0;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=c.Kuota+IF(a.sisa_cuti<c.carryforwardleave,if(a.sisa_cuti<0,a.sisa_cuti,0),IF(a.kuota=0,0,c.carryforwardleave)), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=c.Kuota+IF(a.sisa_cuti<c.carryforwardleave,a.sisa_cuti,IF(a.kuota=0,0,c.carryforwardleave)) where c.master_cuti_type_id=2 and DAY(b.join_date) = DAY(now()) and month(b.join_date) = month(now()) and c.iscarryforward=1;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id ,'Success Anniversary' ,now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id where timestampdiff(day,b.join_date,NOW())>=365 and c.master_cuti_type_id=2 and DAY(b.join_date) = DAY(now()) and month(b.join_date) = month(now()) and c.iscarryforward=1;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.kuota=0,(timestampdiff(month,b.join_date,NOW())+1)+a.cuti_terpakai,(c.Kuota+IF(a.sisa_cuti<c.carryforwardleave,if(a.sisa_cuti<0,a.sisa_cuti,0),IF(a.kuota=0,0,c.carryforwardleave)))), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=IF(a.kuota=0,timestampdiff(month,b.join_date,NOW())+1,(c.Kuota+IF(a.sisa_cuti<c.carryforwardleave,a.sisa_cuti,IF(a.kuota=0,0,c.carryforwardleave)))) where c.master_cuti_type_id=1 and DAY(now()) ='1' and month(now())='1' and c.iscarryforward=1;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id ,'Success Annually' ,now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id where c.master_cuti_type_id=1 and DAY(now()) ='1' and month(now())='1' and c.iscarryforward=1;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.kuota=0,(timestampdiff(month,b.join_date,NOW())+1)+a.cuti_terpakai,(c.Kuota+if(a.sisa_cuti<0,a.sisa_cuti,0))), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=IF(a.kuota=0,timestampdiff(month,b.join_date,NOW())+1,c.Kuota)
where c.master_cuti_type_id=1 and DAY(now()) ='1' and month(now())='1' and c.iscarryforward=0;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id ,'Success Annually' ,now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id where  c.master_cuti_type_id=1 and DAY(now()) ='1' and month(now())='1' and c.iscarryforward=0;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.kuota=0,(timestampdiff(month,b.join_date,NOW())+1)+a.cuti_terpakai,(c.Kuota+IF(a.sisa_cuti<c.carryforwardleave,if(a.sisa_cuti<0,a.sisa_cuti,0),IF(a.kuota=0,0,c.carryforwardleave)))), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=IF(a.kuota=0,timestampdiff(month,b.join_date,NOW())+1,(c.Kuota+IF(a.sisa_cuti<c.carryforwardleave,a.sisa_cuti,IF(a.kuota=0,0,c.carryforwardleave)))) where c.master_cuti_type_id=5 and DAY(now()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(now())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=1 and c.cutoffmonth is not null;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id ,'Success Custom Carry Forward' ,now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id where c.master_cuti_type_id=5 and DAY(now()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(now())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=1 and c.cutoffmonth is not null;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.kuota=0,(timestampdiff(month,b.join_date,NOW())+1)+a.cuti_terpakai,(c.Kuota+if(a.sisa_cuti<0,a.sisa_cuti,0))), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=IF(a.kuota=0,timestampdiff(month,b.join_date,NOW())+1,c.Kuota)
where c.master_cuti_type_id=5 and DAY(now()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(now())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=0 and c.cutoffmonth is not null;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id ,'Success Custom Not Carry Forward' ,now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id where  c.master_cuti_type_id=5 and DAY(now()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(now())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=0 and c.cutoffmonth is not null;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=a.sisa_cuti+1, a.kuota=a.kuota+1
where c.master_cuti_type_id=4 and DAY(now()) ='1' and c.cutoffmonth is not null;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id ,'Success Monthly' ,now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id where   c.master_cuti_type_id=4 and DAY(now()) ='1' and c.cutoffmonth is not null;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.sisa_cuti<c.carryforwardleave,a.sisa_cuti,c.carryforwardleave), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=IF(a.sisa_cuti<c.carryforwardleave,a.sisa_cuti,c.carryforwardleave)
where c.master_cuti_type_id=4 and DAY(now()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(now())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=1 and c.cutoffmonth is not null;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id ,'Success Monthly Cutoff Carry Forward' ,now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id where  c.master_cuti_type_id=4 and DAY(now()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(now())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=1 and c.cutoffmonth is not null;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.sisa_cuti<0,a.sisa_cuti,0), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=0
where c.master_cuti_type_id=4 and DAY(now()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(now())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=0 and c.cutoffmonth is not null;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id ,'Success Monthly Cutoff Not Carry Forward' ,now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id where  c.master_cuti_type_id=4 and DAY(now()) =REPLACE(LEFT(c.cutoffmonth, 2), \"-\", \"\") and month(now())=REPLACE(RIGHT(cutoffmonth, 2), \"-\", \"\") and c.iscarryforward=0 and c.cutoffmonth is not null;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=c.Kuota+if(a.sisa_cuti<0,a.sisa_cuti,0),a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0),a.updated_at=now(),a.kuota=c.Kuota where timestampdiff(day,b.join_date,NOW())>=365 and c.master_cuti_type_id=3 and DAY(b.join_date) = DAY(now()) and month(b.join_date) = month(now()) and c.iscarryforward=0 and timestampdiff(year,b.join_date,NOW())<=1;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id ,'Success Anniversary Anniversary Annually' ,now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id where timestampdiff(day,b.join_date,NOW())>=365 and c.master_cuti_type_id=3 and DAY(b.join_date) = DAY(now()) and month(b.join_date) = month(now()) and c.iscarryforward=0 and timestampdiff(year,b.join_date,NOW())<=1;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=c.Kuota+IF(a.sisa_cuti<c.carryforwardleave,if(a.sisa_cuti<0,a.sisa_cuti,0),IF(a.kuota=0,0,c.carryforwardleave)), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=c.Kuota+IF(a.sisa_cuti<c.carryforwardleave,a.sisa_cuti,IF(a.kuota=0,0,c.carryforwardleave)) where c.master_cuti_type_id=3 and DAY(b.join_date) = DAY(now()) and month(b.join_date) = month(now()) and c.iscarryforward=1 and timestampdiff(year,b.join_date,NOW())<=1;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id ,'Success Anniversary Anniversary Annually' ,now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id where timestampdiff(day,b.join_date,NOW())>=365 and c.master_cuti_type_id=3 and DAY(b.join_date) = DAY(now()) and month(b.join_date) = month(now()) and c.iscarryforward=1 and timestampdiff(year,b.join_date,NOW())<=1;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.kuota=0,(timestampdiff(month,b.join_date,NOW())+1)+a.cuti_terpakai,(c.Kuota+IF(a.sisa_cuti<c.carryforwardleave,if(a.sisa_cuti<0,a.sisa_cuti,0),IF(a.kuota=0,0,c.carryforwardleave)))), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=IF(a.kuota=0,timestampdiff(month,b.join_date,NOW())+1,(c.Kuota+IF(a.sisa_cuti<c.carryforwardleave,a.sisa_cuti,IF(a.kuota=0,0,c.carryforwardleave)))) where c.master_cuti_type_id=3 and DAY(now()) ='1' and month(now())='1' and c.iscarryforward=1 and timestampdiff(year,b.join_date,NOW())>1;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id ,'Success Annually Anniversary Annually' ,now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id where c.master_cuti_type_id=3 and DAY(now()) ='1' and month(now())='1' and c.iscarryforward=1 and timestampdiff(year,b.join_date,NOW())>1;

update user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id set a.sisa_cuti=IF(a.kuota=0,(timestampdiff(month,b.join_date,NOW())+1)+a.cuti_terpakai,(c.Kuota+if(a.sisa_cuti<0,a.sisa_cuti,0))), a.cuti_terpakai=IF(a.sisa_cuti<0,ABS(a.sisa_cuti),0), a.updated_at=now(), a.kuota=IF(a.kuota=0,timestampdiff(month,b.join_date,NOW())+1,c.Kuota)
where c.master_cuti_type_id=3 and DAY(now()) ='1' and month(now())='1' and c.iscarryforward=0 and timestampdiff(year,b.join_date,NOW())>1;

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id ,'Success Annually Anniversary Annually' ,now() From user_cuti a inner join users b on a.user_id = b.id inner join cuti c on a.cuti_id = c.id where  c.master_cuti_type_id=3 and DAY(now()) ='1' and month(now())='1' and c.iscarryforward=0 and timestampdiff(year,b.join_date,NOW())>1;

update user_cuti a left join cuti c on a.cuti_id = c.id left join users u on a.user_id = u.id left join shift s on u.shift_id = s.id set a.sisa_cuti=(a.sisa_cuti-1),a.cuti_terpakai=(a.cuti_terpakai+1)
where c.jenis_cuti ='Annual Leave' and s.is_collective = 0 and c.jenis_cuti is NOT null and EXISTS (select * from cuti_bersama WHERE impacttoleave=1 and DAY(now()) =day(dari_tanggal) and month(now())=month(dari_tanggal) and YEAR(now())=YEAR(dari_tanggal) );

INSERT INTO `cutilog` (`usercuti_id`, `update_status`, `date_update`) select a.id ,'Collective Leave' ,now() From user_cuti a left join cuti c on a.cuti_id = c.id left join users u on a.user_id = u.id left join shift s on u.shift_id = s.id 
where c.jenis_cuti ='Annual Leave' and s.is_collective = 0 and c.jenis_cuti is NOT null and EXISTS (select * from cuti_bersama WHERE impacttoleave=1 and DAY(now()) =day(dari_tanggal) and month(now())=month(dari_tanggal) and YEAR(now())=YEAR(dari_tanggal) );

INSERT INTO `cuti_karyawan` (`user_id`, `jenis_cuti`, `tanggal_cuti_start`,`tanggal_cuti_end`,`keperluan`,`backup_user_id`,`status`,`created_at`,`updated_at`,`total_cuti`,`temp_kuota`,`temp_cuti_terpakai`,`temp_sisa_cuti`) select a.user_id,a.cuti_id,cast(now() as date),cast(now() as date) ,z.description,a.user_id,2,now(),now(),1,a.kuota,a.cuti_terpakai,a.sisa_cuti From user_cuti a left join cuti c on a.cuti_id = c.id left join users u on a.user_id = u.id left join shift s on u.shift_id = s.id 
left join cuti_bersama z on z.dari_tanggal = cast(now() as date)
where c.jenis_cuti ='Annual Leave' and s.is_collective = 0 and c.jenis_cuti is NOT null and EXISTS (select * from cuti_bersama x WHERE x.impacttoleave=1 and DAY(now()) =day(x.dari_tanggal) and month(now())=month(x.dari_tanggal) and YEAR(now())=YEAR(x.dari_tanggal) );

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
