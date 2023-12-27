<?php

use common\components\Config;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Item */
/* @var $form yii\widgets\ActiveForm */

$categoryRequestId = Yii::$app->request->get('category_id');
if ($categoryRequestId) {
    $categoryMap = [$categoryRequestId => Config::CATEGORY_MAP[$categoryRequestId]];
} else {
    $categoryMap = Config::CATEGORY_MAP;
}
?>

<div class="item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if ($model->parent_id) : ?>
        <?= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map(backend\models\Item::getCategories($model->category_id), 'id', 'title')) ?>
    <?php else: ?>

        <?php
        if ($categoryRequestId) {
            $parents = backend\models\Item::getCategories($categoryRequestId);
        } else {
            $parents = [];
        }

        if (!empty($parents)) {
            $disabled = false;
        } else {
            $disabled = true;
        }
        ?>

        <?= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map($parents, 'id', 'title'), ['disabled' => $disabled]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea() ?>

    <?= $form->field($model, 'category_id')->dropDownList($categoryMap) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
