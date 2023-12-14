<?php

use common\components\Config;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if ($model->parent_id) : ?>
        <?= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map(backend\models\Item::getCategories($model->category_id), 'id', 'title')) ?>
    <?php else: ?>
        <?= $form->field($model, 'parent_id')->dropDownList([], ['disabled' => true]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_id')->dropDownList(Config::CATEGORY_MAP) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
