<?php

/* @var $this View */

/* @var $content string */

use backend\assets\AppAsset;
use common\components\Config;
use common\models\Item;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\web\View;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>


    <?php
    NavBar::begin([
        'brandLabel' => 'Archimapa',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'Objects', 'url' => ['/objects/index']],
        ['label' => 'Images', 'url' => ['/image/index']],
        ['label' => 'Items', 'url' => ['/item/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
        $navItems = [];
        if (Yii::$app->user->isGuest) {
            array_push($navItems, ['label' => 'Sign In', 'url' => ['/user/login']], ['label' => 'Sign Up', 'url' => ['/user/register']]);
        } else {
            array_push($navItems, ['label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']]
            );
        }
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>


    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-2 sidebar-offcanvas" id="sidebar" role="navigation">
                <div class="list-group">
                    <?php
                    echo kartik\sidenav\SideNav::widget([
                        'type' => kartik\sidenav\SideNav::TYPE_PRIMARY,
                        'encodeLabels' => false,
                        'items' => [
                            ['label' => Config::CATEGORY_MAP[Config::BUILDING_TYPE], 'url' => ['/item/index/?category_id=' . Config::BUILDING_TYPE]],
                            ['label' => Config::CATEGORY_MAP[Config::BUILDING_SUB_TYPE], 'url' => ['/item/index/?category_id=' . Config::BUILDING_SUB_TYPE]],
                            ['label' => Config::CATEGORY_MAP[Config::ARCHITECT_STYLE], 'url' => ['/item/index/?category_id=' . Config::ARCHITECT_STYLE]],
                            ['label' => Config::CATEGORY_MAP[Config::ARCHITECT_SUB_STYLE], 'url' => ['/item/index/?category_id=' . Config::ARCHITECT_SUB_STYLE]],
                            ['label' => Config::CATEGORY_MAP[Config::ARCHITECT], 'url' => ['/item/index/?category_id=' . Config::ARCHITECT]],
                            ['label' => 'Користувачі', 'url' => ['user/index']]
                        ]
                    ])
                    ?>
                </div>
            </div>
            <!--sidebar-offcanvas-->

            <div class="col-xs-12 col-sm-9" id="content">
                <?= \yii\widgets\Breadcrumbs::widget([
                    'links' => $this->params['breadcrumbs'] ?? [],
                ]) ?>

                <?= $content ?>

            </div>
            <!--content-->

        </div>
    </div>

    <footer class="footer mt-auto py-3 text-muted">
        <div class="container">
            <p class="float-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
            <p class="float-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
