<?php

namespace backend\components;

use yii\filters\AccessControl;
use yii\filters\Cors;
use yii\web\Controller;

class BackendController extends Controller
{
    public function behaviors(): array
    {

        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['*'], // add more
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'actions' => ['create', 'update'],
                    'allow' => true,
                    'roles' => ['admin'], // add more
                ],
                [
                    'actions' => ['index', 'view'],
                    'allow' => true,
                    'roles' => ['@'], // add more
                ],
//                [
//                    'allow' => true,
//                    'roles' => ['@'], // add more
//                ],
            ],
        ];

        return $behaviors;
    }
}
