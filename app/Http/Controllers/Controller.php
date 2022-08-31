<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Config::set('database.default',session('db_name','mysql'));
            if(get_setting('app_debug') == 'false')
            {
                Config::set('app.debug', false );
            }
            else
            {
                Config::set('app.debug', true );
            }

            if(!empty(get_setting('backup_mail')))
            {
                Config::set('backup.notifications.mail.to', get_setting('backup_mail'));
            }

            Config::set('mail.driver', get_setting('mail_driver'));
            Config::set('mail.host', get_setting('mail_host'));
            Config::set('mail.port', get_setting('mail_port'));
            Config::set('mail.from', ['address' => get_setting('mail_username'), 'name' => get_setting('mail_name') ]);
            Config::set('mail.username', get_setting('mail_username'));
            Config::set('mail.password', get_setting('mail_password'));
            Config::set('mail.encryption', get_setting('mail_encryption'));

            $language = empty(get_setting('language')) ? 'en' : get_setting('language') ;
            App::setLocale($language);
            $timezone = empty(get_setting('timezone')) ? "Asia/Bangkok" : get_setting('timezone') ;
            date_default_timezone_set($timezone);

            return $next($request);
        });
    }
}
