<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

use common\components\Config;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Login';

$this->params['breadcrumbs'][] = $this->title;
$parameters = [
    'redirect_uri'  => Config::GOOGLE_REDIRECT_URI,
    'response_type' => 'code',
    'client_id'     => Config::GOOGLE_CLIENT_ID,
    'scope'         => implode(' ', Config::GOOGLE_SCOPES),
];
$uri = Config::GOOGLE_AUTH_URI . '?' . http_build_query($parameters);

?>
<div class="site-login">
    <div class="mt-5 offset-lg-3 col-lg-6">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>Please fill out the following fields to login:</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <a href="<?= $uri ?>">Google</a>
            <?php var_dump($uri); ?>
            <div class="form-group">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
