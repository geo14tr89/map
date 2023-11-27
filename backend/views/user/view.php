<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Security', ['security', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php if(Yii::$app->user->can('deleteUser') ): ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:' . \Yii::$app->params['dateFormat']['php']]
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:' . \Yii::$app->params['dateFormat']['php']]
            ],
            'role' =>
                [
                    'label' => 'User',
                    'value' => implode(', ', array_keys(Yii::$app->authManager->getRolesByUser($model->id)))
                ],
        ],
    ]) ?>

</div>
