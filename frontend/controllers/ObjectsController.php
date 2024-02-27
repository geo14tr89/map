<?php

namespace frontend\controllers;

use common\models\Image;
use common\models\ObjectItem;
use common\models\Objects;
use frontend\components\FrontendController;
use frontend\models\ObjectResponse;
use frontend\models\ObjectsForm;
use frontend\models\SingleObjectResponse;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\web\Response;

class ObjectsController extends FrontendController
{

    /**
     * @return array
     */
    public function actionIndex(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $params = Yii::$app->request->getQueryParams();

        $model = new ObjectsForm();
        if ($model->load($params, '') && $model->validate()) {
            $query = Objects::getObjectsByPolygon($model->north, $model->south, $model->west, $model->east);
            $query = $model->filter($query);

            return $query->asArray()->all();
        }

        return $model->getErrors();
    }

    public function actionView(int $id): SingleObjectResponse
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $mapObject = Objects::getObject($id);

        $breadCrumbs = Yii::$app->breadcrumb->getBreadCrumbsByObjectId($mapObject->id);

        return new SingleObjectResponse($mapObject, $breadCrumbs);
    }

    /**
     * Creates a new Objects model.
     * objects/add
     * @return mixed
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionAdd()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $params = Yii::$app->getRequest()->getBodyParams();

        $model = new \backend\models\Objects();
        $model->title = $params['title'];
        $model->description = $params['description'];
        $model->latitude = (float)$params['latitude'];
        $model->longitude = (float)$params['longitude'];
        $model->custom_fields = $params['customFields'];
        if ($model->save() === false) {
            return $model->getFirstErrors();
        }

        if (!empty($params['items'])) {
            $categoryIds = explode(',', $params['items']);
            ObjectItem::addObjectItems($model->id, $categoryIds);
        }

        $count = count($_FILES);
        if ($count > 1) {
            for ($i = 0; $i < $count; $i++) {
                $image = \yii\web\UploadedFile::getInstanceByName('photo' . $i);
                $backendImage = new Image();
                $backendImage->uploadExternalFile($image, $model->id);
            }
        }

        $image = \yii\web\UploadedFile::getInstanceByName('photo0');
        $backendImage = null;
        if ($image !== null) {
            $backendImage = new Image();
            $backendImage->uploadExternalFile($image, $model->id);
        }

        return new ObjectResponse($model, $backendImage);
    }

    /**
     * @param int $id
     * @return SingleObjectResponse
     * @throws Exception
     * @throws InvalidConfigException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionEdit(int $id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $params = Yii::$app->getRequest()->getBodyParams();

        $object = \backend\models\Objects::findOne($id);
        $object->title = $params['title'];
        $object->description = $params['description'];
        $object->latitude = (float)$params['latitude'];
        $object->longitude = (float)$params['longitude'];
        $object->custom_fields = $params['customFields'];
        if ($object->update() === false) {
            throw new Exception('Error per updating object ID = ' . $id);
        }

        if (!empty($params['items'])) {
            $categoryIds = explode(',', $params['items']);
            ObjectItem::updateObjectItems($object->id, $categoryIds);
        }

        return $this->actionView($id);
    }

    public function actionTest()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return Yii::$app->db->dsn;
    }

    /**
     * @url objects/item/:id
     * @param $id
     * @return array
     */
    public function actionItem($id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $result = Objects::getObjectsByItemId($id);

        return $result;
    }
}
