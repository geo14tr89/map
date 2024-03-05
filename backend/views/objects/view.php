<?php

use backend\models\DynamicModels\CustomFieldsModel;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Objects */
/* @var $custom_fields_model CustomFieldsModel */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Objects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
    <div class="objects-view">

        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'latitude',
                'longitude',
                'title',
                'description:ntext',
            ],
        ]) ?>

        <h2>Custom Fields</h2>

        <?= DetailView::widget([
            'model' => $custom_fields_model,
            'attributes' => [
                'date_built',
                'building_type',
                'architect',
                'architectural_style',
                'architectural_substyle',
                'building_subtype'
            ],
        ]) ?>

    </div>
<?php
