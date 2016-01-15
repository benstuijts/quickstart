<?php

return [
    
    'app' => [
        'name' => 'HockeyTips',
        'url' => 'https://hockeytips-stuijts.c9.io/v1.0/public',
        'author' => 'Ben Stuijts',
        'hash' => [
            'algo' => PASSWORD_BCRYPT,
            'cost' => 10
        ]
    ],
    
    'db' => [
        'driver' => 'mysql',
        'host' => getenv('IP'),
        'name' => 'hockeytips',
        'username' => getenv('C9_USER'),
        'password' => '',
        'port' => 3306,
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => ''
    ],
    
    'auth' => [
        'session' => 'user_id',
        'remember' => 'user_r'
    ],
    
    'facebook' => [
        'appID' => '1696173763952288',
        'appSecret' => '4d895d3338141489c5d8c350db7adb22',
        'APIversion' => 'v2.5'
    ],
    
    'mail' => [
        'secret' => 'key-80f4c90878ac204451b9080ebd53c1b1',
        'domain' => 'sandboxb0f47cd8d2d144308e7cdd9c4ea3e1d6.mailgun.org',
        'from' => 'welkom@hockeytips.eu'
    ],

    'twig' => [
        'debug' => true
    ],
    
    'csrf' => [
        'key' => 'scrf_token',
        'session' => 'csrf_token'    
    ]
    
    
];