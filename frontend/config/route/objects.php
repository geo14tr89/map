<?php

use yii\rest\UrlRule;

return [
    [
        'class' => UrlRule::class,
        'pluralize' => false,
        'controller' => [
            'objects',
        ],
        'extraPatterns' => [
            'POST add' => 'add',
            'GET test' => 'test',
            'GET edit' => 'edit',
            'GET item/<id:\d+>' => 'item',
        ]
    ]
];
