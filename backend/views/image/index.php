<?php

use backend\models\Image;
use backend\models\search\ImageSearch;
use common\models\Objects;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel ImageSearch */

$this->title = 'Images';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Image', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => static function ($model, $key, $index, $widget) {
            if ($model->status === 2) {
                return ['style' => 'background-color: #FFFFE0'];
            }
            return ['style' => 'background-color: white'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'object_id',
                'value' => static function ($data) {
                    return Objects::find(['id' => $data['object_id']])->asArray()->one()['title'] . " ({$data['object_id']})";
                },
            ],
            'preview_url:ntext',
            [
                'attribute' => 'preview_url',
                'label' => 'Preview Image',
                'format' => 'html',
                'value' => static function ($data) {
                    return Html::img($data['preview_url'],
                        ['width' => '200px']);
                },
            ],
            'full_url:ntext',
            'title',
            [
                'attribute' => 'status',
                'value' => static function ($data) {
                    return Image::STATUS_IMAGE_MAP[$data['status']];
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => static function ($url, $model) {
                        $url = Url::to(
                            [
                                '/image/update',
                                'id' => $model->id,
                            ]
                        );
                        return Html::a(
                            'Approve',
                            $url,
                            ['class' => 'btn btn-success',
                                'data' => [
                                    'method' => 'post',
                                    'params' => [
                                        'status' => 2,
                                    ],
                                ]
                            ],
                        );
                    },
                    'delete' => static function ($url, $model) {
                        $url = Url::to(
                            [
                                '/image/delete',
                                'id' => $model->id,
                            ]
                        );
                        return Html::a(
                            'Delete',
                            $url,
                            ['class' => 'btn btn-danger',
                                'data' => [
                                    'method' => 'post',
                                    'params' => [
                                        'status' => 2,
                                    ],
                                ]
                            ],
                        );
                    },
                ]
            ],
        ],
    ]) ?>


</div>
