<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model frontend\models\SignupForm */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Update User: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Security', ['security', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <div class="user-form col-sm-4 col-md-3">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

        <?php if(Yii::$app->user->can('admin')) :?>
            <?= Html::dropDownList('role', array_keys(Yii::$app->authManager->getRolesByUser($model->id)), User::getRolesList(), ['multiple' => true]); ?>
        <?php endif; ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
