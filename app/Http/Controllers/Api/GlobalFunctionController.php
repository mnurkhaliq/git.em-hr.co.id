<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DateTime;
use DateTimeZone;

class GlobalFunctionController extends Controller
{
    public function getDate($timezone, $format = 'Y-m-d')
    {
        if ($timezone == 'WIB') {
            $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        } else if ($timezone == 'WITA') {
            $date = new DateTime("now", new DateTimeZone('Asia/Shanghai'));
        } else if ($timezone == 'WIT') {
            $date = new DateTime("now", new DateTimeZone('Asia/Tokyo'));
        } else {
            $date = new DateTime("now");
        }

        return $date->format($format);
    }

    public function getServerTimezone()
    {
        $timezone = null;
        $utc = date('Z') / 3600;
        if ($utc == '7') {
            $timezone = 'WIB';
        } else if ($utc == '8') {
            $timezone = 'WITA';
        } else if ($utc == '9') {
            $timezone = 'WIT';
        }

        return $timezone;
    }
}
