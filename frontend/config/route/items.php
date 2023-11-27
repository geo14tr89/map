<?php

use yii\rest\UrlRule;

return [
    [
        'class' => UrlRule::class,
        'pluralize' => false,
        'controller' => [
            'items',
        ],
        'extraPatterns' => [
            'GET category/<id:\d+>' => 'category',
        ]
    ]
];
