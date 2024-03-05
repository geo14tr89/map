<?php

use backend\models\DynamicModels\CustomFieldsModel;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Objects */
/* @var $form yii\widgets\ActiveForm */
/* @var $custom_fields_model CustomFieldsModel */
?>

<div class="objects-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'latitude')->textInput() ?>

    <?= $form->field($model, 'longitude')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php
    if (isset($custom_fields_model->date_built)) {
        echo $form->field($custom_fields_model, 'date_built')->textInput();
    }
    if (isset($custom_fields_model->building_type)) {
        echo $form->field($custom_fields_model, 'building_type')->textInput();
    }
    if (isset($custom_fields_model->architect)) {
        echo $form->field($custom_fields_model, 'architect')->textInput();
    }
    if (isset($custom_fields_model->architectural_style)) {
        echo $form->field($custom_fields_model, 'architectural_style')->textInput();
    }
    if (isset($custom_fields_model->architectural_substyle)) {
        echo $form->field($custom_fields_model, 'architectural_substyle')->textInput();
    }
    if (isset($custom_fields_model->building_subtype)) {
        echo $form->field($custom_fields_model, 'building_subtype')->textInput();
    }
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
