<?php

return [
    'driver' => 'smtp',
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'from' => [
        'address' => 'diaoseydina62@gmail.com',
        'name' => 'Système Electoral',
    ],
    'encryption' => 'tls',
    'username' => 'diaoseydina62@gmail.com',
    'password' => 'lgoo pbck agao sewq',
    'sendmail' => '/usr/sbin/sendmail -bs',
    'pretend' => false,
    'verify_peer' => false,
];
