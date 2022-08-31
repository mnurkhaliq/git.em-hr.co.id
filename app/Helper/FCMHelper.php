<?php

namespace App\Helper;

use App\User;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Facades\FCMGroup;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;

class FCMHelper
{
    public static function sendTopicNotification($config)
    {
        if ($config['app_type'] == config('constants.apps.emhr_mobile_attendance')) {
            config(['fcm.http.server_key' => config('constants.firebase.fcm_server_key_attendance')]);
            config(['fcm.http.sender_id' => config('constants.firebase.fcm_sender_id_attendance')]);
        } else {
            config(['fcm.http.server_key' => config('constants.firebase.fcm_server_key')]);
            config(['fcm.http.sender_id' => config('constants.firebase.fcm_sender_id')]);
        }

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($config);
        $data = $dataBuilder->build();

        $topic = new Topics();
        $topic->topic($config['topic']);

        try {
            return FCM::sendToTopic($topic, null, null, $data);
        } catch (\Exception $e) {
            info($e->getMessage());
            return false;
        }
    }

    public static function sendToDevice($data, $config)
    {
        config(['fcm.http.server_key' => config('constants.firebase.fcm_server_key')]);
        config(['fcm.http.sender_id' => config('constants.firebase.fcm_sender_id')]);

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);
        $option = $optionBuilder->build();

        $notificationBuilder = new PayloadNotificationBuilder($config['title']);
        $notificationBuilder->setBody($config['content'])->setSound('default');
        $notification = $notificationBuilder->build();

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'type' => $config['type'],
            'data' => $data,
        ]);
        $data = $dataBuilder->build();

        $token = $config['firebase_token'];

        try {
            return FCM::sendTo($token, $option, $notification, $data);
        } catch (\Exception $e) {
            info($e->getMessage());
            return false;
        }
    }

    public static function sendAttendance($config)
    {
        if ($config['app_type'] == config('constants.apps.emhr_mobile_attendance')) {
            config(['fcm.http.server_key' => config('constants.firebase.fcm_server_key_attendance')]);
            config(['fcm.http.sender_id' => config('constants.firebase.fcm_sender_id_attendance')]);
        } else {
            config(['fcm.http.server_key' => config('constants.firebase.fcm_server_key')]);
            config(['fcm.http.sender_id' => config('constants.firebase.fcm_sender_id')]);
        }

        $optionBuilder = new OptionsBuilder();
        // $optionBuilder->setTimeToLive(60*1);
        $optionBuilder->setPriority('high');
        $option = $optionBuilder->build();

        // $notificationBuilder = new PayloadNotificationBuilder($config['title']);
        // $notificationBuilder->setBody($config['content'])->setSound('attendance');
        // $notification = $notificationBuilder->build();

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'title' => $config['title'],
            'body' => $config['content'],
            'type' => 'attendance',
        ]);
        $data = $dataBuilder->build();

        $token = $config['firebase_token'];

        try {
            return FCM::sendTo($token, $option, null, $data);
        } catch (\Exception $e) {
            info($e->getMessage());
            return false;
        }
    }

    public static function sendAttendanceIos($config)
    {
        if ($config['app_type'] == config('constants.apps.emhr_mobile_attendance')) {
            config(['fcm.http.server_key' => config('constants.firebase.fcm_server_key_attendance')]);
            config(['fcm.http.sender_id' => config('constants.firebase.fcm_sender_id_attendance')]);
        } else {
            config(['fcm.http.server_key' => config('constants.firebase.fcm_server_key')]);
            config(['fcm.http.sender_id' => config('constants.firebase.fcm_sender_id')]);
        }

        // $optionBuilder = new OptionsBuilder();
        // $optionBuilder->setTimeToLive(60*1);
        // $optionBuilder->setPriority('high');
        // $option = $optionBuilder->build();

        $notificationBuilder = new PayloadNotificationBuilder($config['title']);
        $notificationBuilder->setBody($config['content'])->setSound('attendance_ringtone.wav');
        $notification = $notificationBuilder->build();

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'title' => $config['title'],
            'body' => $config['content'],
            'type' => 'attendance',
        ]);
        $data = $dataBuilder->build();

        $token = $config['firebase_token'];

        try {
            return FCM::sendTo($token, null, $notification, $data);
        } catch (\Exception $e) {
            info($e->getMessage());
            return false;
        }
    }

    public static function createGroup($groupName, $tokens)
    {
        config(['fcm.http.server_key' => config('constants.firebase.fcm_server_key')]);
        config(['fcm.http.sender_id' => config('constants.firebase.fcm_sender_id')]);

        try {
            return FCMGroup::createGroup($groupName, $tokens);
        } catch (\Exception $e) {
            info($e->getMessage());
            return false;
        }
    }

    public static function addToGroup($groupName, $groupToken, $tokens)
    {
        config(['fcm.http.server_key' => config('constants.firebase.fcm_server_key')]);
        config(['fcm.http.sender_id' => config('constants.firebase.fcm_sender_id')]);

        try {
            return FCMGroup::addToGroup($groupName, $groupToken, $tokens);
        } catch (\Exception $e) {
            info($e->getMessage());
            return false;
        }
    }

    public static function removeTokens($firebaseTokens)
    {
        try {
            return User::whereIn('firebase_token', $firebaseTokens)->update(['firebase_token' => null]);
        } catch (\Exception $e) {
            info($e->getMessage());
            return false;
        }
    }
}
