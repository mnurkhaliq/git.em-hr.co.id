<?php

namespace App\Console;

use App\Models\ConfigDB;
use App\Models\ShiftDetail;
use Artisan;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Config;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // if (Carbon::now()->format('H:i') == '09:00') {
        //     foreach (ConfigDB::whereNotNull('db_name')->where('due_date', Carbon::now()->startOfDay()->addDays(6))->get() as $item) {
        //         $endpoint = env('URL_CRM').'project/mail/'.$item->id;
        //         $client = new \GuzzleHttp\Client([
        //             'verify' => false
        //         ]);
        //         $response = $client->request('GET', $endpoint, [
        //             'headers' => ['Authorization' => env('CRM_API_KEY')]
        //         ]);

        //         info("Send email license reminder db ".$item->db_name." with status code ".$response->getStatusCode());
        //     }
        // }

        // foreach (ConfigDB::whereNotNull('db_name')->where('due_date', '>=', Carbon::now()->startOfDay())->whereHas('modules.module', function($query) {
        //     $query->where('emhr_id', 17);
        // })->get() as $item) {
        //     // info("Scheduler check database : ".$item->db_name);
        //     Config::set('database.default', $item->db_name);
        //     try {
        //         \DB::connection()->getPdo();
        //         // info("Scheduler check database active : ".$item->db_name);
        //         if (!empty(get_setting('attendance_notification')) && get_setting('attendance_notification')) {
        //             $gap = !empty(get_setting('attendance_notification_before')) ? get_setting('attendance_notification_before') : 0;
        //             foreach (ShiftDetail::whereHas('shift', function($query) {
        //                 return $query->whereNull('deleted_at');
        //             })->where('day', date('l'))->get() as $value) {
        //                 if ($value->clock_in == Carbon::now()->addMinutes($gap)->format('H:i')) {
        //                     $command_params['--database'] = $item->db_name;
        //                     $command_params['--shift'] = $value->shift->id;
        //                     $command_params['--type'] = 'clock_in';
        //                     $command_params['--gap'] = $gap;
        //                     Artisan::call('notif:attendance', $command_params);
        //                     info("Clock IN Reminder with gap : ".$gap.", shift : ".$value->shift->name.", clock time : ".$value->clock_in.", database : ".$item->db_name);
        //                 } 
        //                 if ($value->clock_out == Carbon::now()->addMinutes($gap)->format('H:i')) {
        //                     $command_params['--database'] = $item->db_name;
        //                     $command_params['--shift'] = $value->shift->id;
        //                     $command_params['--type'] = 'clock_out';
        //                     $command_params['--gap'] = $gap;
        //                     Artisan::call('notif:attendance', $command_params);
        //                     info("Clock OUT Reminder with gap : ".$gap.", shift : ".$value->shift->name.", clock time : ".$value->clock_out.", database : ".$item->db_name);
        //                 }
        //             }
        //         }
        //     } catch (\Exception $e) {}
        // }
        // Config::set('database.default', session('db_name', 'mysql'));

        // if(!empty(get_setting('backup_mail')))
        // {
        //     Config::set('backup.notifications.mail.to', get_setting('backup_mail'));
        // }
        // if(!empty(get_setting('backup_size')))
        // {
        //     Config::set('backup.cleanup.default_strategy.delete_oldest_backups_when_using_more_megabytes_than', get_setting('backup_size'));
        //     Config::set('backup.monitor_backups.0.health_checks.Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumStorageInMegabytes', get_setting('backup_size'));
        // }

        // Config::set('mail.driver', get_setting('mail_driver'));
        // Config::set('mail.host', get_setting('mail_host'));
        // Config::set('mail.port', get_setting('mail_port'));
        // Config::set('mail.from', ['address' => get_setting('mail_username'), 'name' => get_setting('mail_name') ]);
        // Config::set('mail.username', get_setting('mail_username'));
        // Config::set('mail.password', get_setting('mail_password'));
        // Config::set('mail.encryption', get_setting('mail_encryption'));

        // foreach (get_schedule() as $key => $value) {
        //     $command_backup = '';
        //     $command_params = [];

        //     if ($value->backup_type == 1) {
        //         $command_backup = 'backup:run';
        //         $this->runBackup($value, $command_backup, $command_params);
        //     } else if ($value->backup_type == 2) {
        //         $command_backup = 'backup:run';
        //         $command_params['--only-db'] = true;
        //         $this->runBackup($value, $command_backup, $command_params);
        //     } else if ($value->backup_type == 3) {
        //         $command_backup = 'backup:run';
        //         $command_params['--only-files'] = true;
        //         $this->runBackup($value, $command_backup, $command_params);
        //     } else if ($value->backup_type == 4) {
        //         $command_backup = 'backup:monitor';
        //         $this->runBackup($value, $command_backup, $command_params);
        //     } else if ($value->backup_type == 5) {
        //         $command_backup = 'backup:clean';
        //         $this->runBackup($value, $command_backup, $command_params);
        //     }
        // }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    // private function runBackup($value, $command_backup, $command_params) {
    //     if ($value->recurring == 1) {
    //         if (date('H:i', strtotime($value->time)) == date('H:i')) {
    //             Artisan::call($command_backup, $command_params);
    //             info($value);
    //         }
    //     } else if ($value->recurring == 2) {
    //         if (date('l') == 'Sunday' && date('H:i', strtotime($value->time)) == date('H:i')) {
    //             Artisan::call($command_backup, $command_params);
    //             info($value);
    //         }
    //     } else if ($value->recurring == 3) {
    //         if (date('d') == '1' && date('H:i', strtotime($value->time)) == date('H:i')) {
    //             Artisan::call($command_backup, $command_params);
    //             info($value);
    //         }
    //     } else if ($value->recurring == 4) {
    //         if ($value->date == date('Y-m-d') && date('H:i', strtotime($value->time)) == date('H:i')) {
    //             Artisan::call($command_backup, $command_params);
    //             info($value);
    //         }
    //     }
    // }
}
