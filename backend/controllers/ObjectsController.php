<?php

namespace backend\controllers;

use backend\components\BackendController;
use backend\models\DynamicModels\CustomFieldsModel;
use backend\models\Objects;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

/**
 * ObjectsController implements the CRUD actions for Objects model.
 */
class ObjectsController extends BackendController
{

    /**
     * Lists all Objects models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Objects::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Objects model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $customFields = Json::decode($model->custom_fields);
        $customFieldsModel = new CustomFieldsModel();
        $customFieldsModel->setAttributes($customFields);

        return $this->render('view', [
            'model' => $model,
            'custom_fields_model' => $customFieldsModel,
        ]);
    }

    /**
     * Creates a new Objects model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Objects();

        $customFieldsModel = new CustomFieldsModel();
        $customFields = $customFieldsModel->getDefaultStructure();
        $customFieldsModel->setAttributes($customFields);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $customFieldsData = $this->request->post()['CustomFieldsModel'];
                $model->custom_fields = Json::encode($customFieldsData);
                $model->save();

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'custom_fields_model' => $customFieldsModel,
        ]);
    }

    /**
     * Updates an existing Objects model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $customFields = Json::decode($model->custom_fields);
        $customFieldsModel = new CustomFieldsModel();
        $customFieldsModel->setAttributes($customFields);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $customFieldsData = $this->request->post()['CustomFieldsModel'];
            $model->custom_fields = Json::encode($customFieldsData);
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'custom_fields_model' => $customFieldsModel
        ]);
    }

    /**
     * Deletes an existing Objects model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Objects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Objects the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Objects::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
