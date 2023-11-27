<?php

use yii\rest\UrlRule;

return [
    [
        'class' => UrlRule::class,
        'pluralize' => false,
        'controller' => [
            'auth',
        ],
        'extraPatterns' => [
            'POST login' => 'login',
            'POST register' => 'register',
            'GET google' => 'google'
        ]
    ]
];
