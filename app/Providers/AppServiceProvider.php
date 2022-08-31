<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        session()->put('locale', 'id');
        app()->setLocale(session()->get('locale'));


        Schema::defaultStringLength(191);
//        if(get_setting('app_debug') == 'false')
//        {
//            \Config::set('app.debug', false );
//        }
//        else
//        {
//            \Config::set('app.debug', true );
//        }
//
//        if(!empty(get_setting('backup_mail')))
//        {
//            \Config::set('backup.notifications.mail.to', get_setting('backup_mail'));
//        }
//
//        \Config::set('mail.driver', get_setting('mail_driver'));
//        \Config::set('mail.host', get_setting('mail_host'));
//        \Config::set('mail.port', get_setting('mail_port'));
//        \Config::set('mail.from', ['address' => get_setting('mail_username'), 'name' => get_setting('mail_name') ]);
//        \Config::set('mail.username', get_setting('mail_username'));
//        \Config::set('mail.password', get_setting('mail_password'));
//        \Config::set('mail.encryption', get_setting('mail_encryption'));

        $timezone = empty(get_setting('timezone')) ? "Asia/Bangkok" : get_setting('timezone') ;
        date_default_timezone_set($timezone);

        // find setting URL
        Validator::extend('start_range_to', 'App\Rules\Custom@startRangeTo');
        Validator::replacer('start_range_to', 'App\Rules\Custom@startRangeToMessage');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path('Helper/DashboardHelper.php');
        require_once app_path('Helper/SettingHelper.php');
        require_once app_path('Helper/AttendanceHelper.php');
        require_once app_path('Helper/VisitHelper.php');
        require_once app_path('Helper/AsiaHelper.php');
        require_once app_path('Helper/EmporeHelper.php');
        require_once app_path('Helper/GeneralHelper.php');
        require_once app_path('Helper/ApprovalHelper.php');
        require_once app_path('Helper/StructureHelper.php');
        require_once app_path('Helper/PayrollHelper.php');
        require_once app_path('Helper/ModuleHelper.php');
        require_once app_path('Helper/KpiHelper.php');
        require_once app_path('Helper/BranchHelper.php');
        require_once app_path('Helper/DivisionHelper.php');
        require_once app_path('Helper/PositionHelper.php');
        require_once app_path('Helper/CareerHelper.php');
        require_once app_path('Helper/RecruitmentHelper.php');
        Passport::ignoreMigrations();
    }
}
