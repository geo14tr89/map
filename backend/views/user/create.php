<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

/**
 * @var $this yii\web\View
 * @var $model frontend\models\SignupForm
 * @var $form ActiveForm
 */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($model, 'username') ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?php if(Yii::$app->user->can('admin')) :?>
                <?= Html::dropDownList('role', null, User::getRolesList(), ['multiple' => true]); ?>
            <?php endif; ?>
            <div class="form-group">
                <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div><!-- create -->
