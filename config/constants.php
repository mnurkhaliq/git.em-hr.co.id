<?php
    return [
        'apps' => [
            'emhr_mobile_attendance' => 78,
            'emhr_mobile' => 79,
        ],
        'firebase' => [
            'fcm_server_key_attendance' => env('FCM_SERVER_KEY_ATTENDANCE'),
            'fcm_sender_id_attendance' => env('FCM_SENDER_ID_ATTENDANCE'),
            'fcm_server_key' => env('FCM_SERVER_KEY'),
            'fcm_sender_id' => env('FCM_SENDER_ID'),
        ]
    ];
