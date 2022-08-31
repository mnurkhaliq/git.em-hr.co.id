<?php

namespace App\Console\Commands;

use App\Helper\FCMHelper;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class NotifAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notif:attendance {--database=} {--shift=} {--type=} {--gap=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attendance reminder notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Config::set('database.default', $this->option('database'));

        $config = [
            'title' => $this->option('type') == 'clock_in' ? 'Clock In Reminder' : 'Clock Out Reminder',
            'content' => ($this->option('type') == 'clock_in' ? 'Its time to clock in' : 'Its time to clock out') . ($this->option('gap') ? ' in ' . $this->option('gap') . ($this->option('gap') > 1 ? ' minutes' : ' minute') : ''),
            'app_type' => config('constants.apps.emhr_mobile_attendance'),
            'firebase_token' => User::where('shift_id', $this->option('shift'))->whereNotNull('firebase_token')->where('os_type', 'android')->pluck('firebase_token')->toArray(),
        ];

        if (count($config['firebase_token'])) {
            $attendanceMobile = FCMHelper::sendAttendance($config);
            $config['app_type'] = config('constants.apps.emhr_mobile');
            $emhrMobile = FCMHelper::sendAttendance($config);
        } else {
            $attendanceMobile = true;
            $emhrMobile = true;
        }

        $config['firebase_token'] = User::where('shift_id', $this->option('shift'))->whereNotNull('firebase_token')->where('os_type', '!=', 'android')->pluck('firebase_token')->toArray();
        
        if (count($config['firebase_token'])) {
            $attendanceMobileIos = FCMHelper::sendAttendanceIos($config);
            $config['app_type'] = config('constants.apps.emhr_mobile_attendance');
            $emhrMobileIos = FCMHelper::sendAttendanceIos($config);
        } else {
            $attendanceMobileIos = true;
            $emhrMobileIos = true;
        }

        Config::set('database.default', session('db_name', 'mysql'));

        if ($attendanceMobile && $emhrMobile && $attendanceMobileIos && $emhrMobileIos) {
            return $this->info('Success');
        } else {
            return $this->error('Something went wrong');
        }
    }
}
