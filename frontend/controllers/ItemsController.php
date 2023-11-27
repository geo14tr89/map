<?php

namespace frontend\controllers;

use common\models\Item;
use Yii;
use yii\web\Response;

class ItemsController extends \frontend\components\FrontendController
{
    public function actionIndex(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return Item::find()->all();
    }

    /**
     * @url /items/category/:id
     * @param $id
     * @return array
     */
    public function actionCategory($id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return Item::find()->where(['category_id' => $id])->all();
    }
}
