<?php

use common\components\BreadCrumb;
use common\components\Config;
use yii\caching\FileCache;

return [
    'aliases' => [
        '@frontendRoot' => dirname(__DIR__, 2) . '/frontend/web/',
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'components' => [
        'cache' => [
            'class' => FileCache::class,
        ],
        'config' => [
            'class' => Config::class,
        ],
        'breadcrumb' => [
            'class' => BreadCrumb::class,
        ],
    ],
];
