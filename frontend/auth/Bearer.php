<?php

namespace frontend\auth;

use Yii;
use yii\filters\auth\HttpBearerAuth;

class Bearer extends HttpBearerAuth
{
    public function handleFailure($response)
    {
        Yii::$app->response->setStatusCode(403);

        return Yii::$app->response->data = [
            'message' => 'You need authorization'
        ];
    }
}
