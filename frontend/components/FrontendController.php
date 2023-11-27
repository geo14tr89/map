<?php

namespace frontend\components;

use frontend\auth\Bearer;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\web\Controller;

class FrontendController extends Controller
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

//        $behaviors['access'] = [
//            'class' => AccessControl::class,
//            'rules' => [
//                [
//                    // All actions
//                    'allow' => true,
//                    'actions' => ['*'], // add more
//                ],
//            ],
//        ];

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['login', 'signup'],
            'only' => ['login', 'signup'],
        ];

        return $behaviors;
    }
}
