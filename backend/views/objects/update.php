<?php

use backend\models\DynamicModels\CustomFieldsModel;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Objects */
/* @var $custom_fields_model CustomFieldsModel */

$this->title = 'Update Objects: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Objects', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
    <div class="objects-update">

        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            'custom_fields_model' => $custom_fields_model
        ]) ?>

    </div>
<?php
