<?php

use yii\rest\UrlRule;

return [
    [
        'class' => UrlRule::class,
        'pluralize' => false,
        'controller' => [
            'photo',
        ],
        'extraPatterns' => [
            'POST add' => 'add',
        ]
    ]
];
