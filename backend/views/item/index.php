<?php

use backend\models\search\ItemSearch;
use common\components\Config;
use common\models\Objects;
use yii\grid\SerialColumn;
use common\models\Item;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel ItemSearch */

$this->title = 'Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Item', ['create', 'category_id' => Yii::$app->request->get('category_id')], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => SerialColumn::class],

            'id',
            [
                'attribute' => 'category_id',
                'value' => static function ($data) {
                    return Config::CATEGORY_MAP[$data['category_id']] . " ({$data['category_id']})";
                },
            ],
            'title',
            'description',
            [
                'class' => ActionColumn::class,
                'contentOptions' => ['width' => '40px'],
                'urlCreator' => static function ($action, Item $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
