<?php

namespace frontend\controllers;

use common\models\Image;
use frontend\components\FrontendController;
use Yii;
use yii\db\Exception;
use yii\web\Response;
use yii\web\UploadedFile;

class PhotoController extends FrontendController
{
    /**
     * photo/add
     * @throws Exception
     */
    public function actionAdd()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $objectId = Yii::$app->request->getBodyParam('object_id');

        $count = count($_FILES);
        if ($count > 1) {
            for ($i = 0; $i < $count; $i++) {
                $image = UploadedFile::getInstanceByName ('photo' . $i);
                $backendImage = new Image();
                $backendImage->uploadExternalFile($image, $objectId);
            }
        }

        $image = UploadedFile::getInstanceByName ('photo0');
        $backendImage = null;
        if ($image !== null) {
            $backendImage = new Image();
            $backendImage->uploadExternalFile($image, $objectId);
        }

        return Image::find()->where(['object_id' => $objectId])->asArray()->all();
    }
}
