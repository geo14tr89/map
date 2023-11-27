<?php

namespace frontend\controllers;

use common\models\Image;
use common\models\ObjectItem;
use common\models\Objects;
use frontend\components\FrontendController;
use frontend\models\ObjectResponse;
use frontend\models\SingleObjectResponse;
use SplQueue;
use Throwable;
use Yii;
use yii\authclient\AuthAction;
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
        $north = $params['north'];
        $south = $params['south'];
        $west = $params['west'];
        $east = $params['east'];

        return Objects::getObjectsByPolygon($north, $south, $west, $east);
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
        $array = [2, 4, 10, 6];

        $a = 'hello';
        $b = 'world';
        list($a, $b) = [$b, $a];
        //return $this->finHighest($array);

        $array1 = [33, 15, 10, 2, 7, 34, 67, 89, 32, 78, 29];
        //return $this->quickSort($array1);

        $this->search('you');

        //$this->selectionSort($array);

//        $array = [];
//        for ($i = 0; $i <= 100; $i++) {
//            $array[] = $i;
//        }
//
//        $low = 0;
//        $high = count($array) - 1;
//
//        while ($low <= $high) {
//            $middle = ($low + $high) / 2;
//            $guess = $array[$middle];
//            if ($guess === 54) {
//                return $middle;
//            }
//
//            if ($guess > 54) {
//                $high = $middle - 1;
//            } else {
//                $low = $middle + 1;
//            }
//        }
//        return null;

        return $array;
        $array = [
            [
                'name' => 'Andrii',
                'age' => 29,
                'status' => 'active',
                'email' => 'andrikkg@ukr.net'
            ],
            [
                'name' => 'Andrii123',
                'age' => 29,
                'status' => 'active',
                'email' => 'andrikkg@ukr.net'
            ],
            [
                'name' => 'Andrii456',
                'age' => 29,
                'status' => 'active',
                'email' => 'andrikkg@ukr.net'
            ],
        ];

        $neededKeys = ['name', 'age'];

        $result = [];

        foreach ($array as $values) {
            $result[] = array_filter($values, function ($value, $key) use ($neededKeys) {
                if (in_array($key, $neededKeys, true)) {
                    return $value;
                }
                return '';
            }, ARRAY_FILTER_USE_BOTH);
        }

        return $result;
    }

    public function findSmallest(array $array)
    {
        $smallest = $array[0];
        $smallEstIndex = 0;
        $count = count($array);
        for ($i = 0; $i < $count; $i++) {
            if (isset($array[$i]) && $array[$i] < $smallest) {
                $smallest = $array[$i];
                $smallEstIndex = $i;
            }
        }
        return $smallEstIndex;
    }

    public function sum(array $array)
    {
        if (empty($array)) {
            return 0;
        }

        $first = $array[0];
        unset($array[0]);
        $array = array_values($array);

        return $first + $this->sum($array);
    }

    public function finHighest(array $array)
    {
        sort($array);
        $result = array_reverse($array);

        return $result[0];
    }

    public function quickSort(array $array)
    {
        $count = count($array);
        if ($count <= 1) {
            return $array;
        }

        $supportKey = array_rand($array);

        $lessThenSupport = [];
        $moreThenSupport = [];

//        for ($i = 1; $i < $count; $i++) {
//            if ($array[$i] < $supportElement) {
//                $lessThenSupport[] = $array[$i];
//            } else {
//                $moreThenSupport[] = $array[$i];
//            }
//        }

        foreach ($array as $item) {
            if ($item === $array[$supportKey]) {
                continue;
            }
            if ($item < $array[$supportKey]) {
                $lessThenSupport[] = $item;
            } else {
                $moreThenSupport[] = $item;
            }
        }

        return array_merge($this->quickSort($lessThenSupport), [$array[$supportKey]], $this->quickSort($moreThenSupport));
    }

    public function search(string $name)
    {
        $searchQueue = new SplQueue();
        $searchQueue->enqueue($name);
        $searched = [];

        while ($searchQueue) {
            $person = $searchQueue->pop();
            if (in_array($person, $searched, true) === false) {
                if ($person === 'Mango') {
                    return true;
                }

                $searchQueue->enqueue($person);
                $searched[] = $person;
            }
        }
        return false;
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
