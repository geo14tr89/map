<?php

use common\components\BreadCrumb;
use common\components\Config;
use yii\BaseYii;

class Yii extends BaseYii
{
    /**
     * @var BaseApplication the application instance
     */
    public static $app;
}

/**
 * Class BaseApplication
 *
 * @property-read Config $config
 * @property-read BreadCrumb $breadcrumb
 */
abstract class BaseApplication extends yii\base\Application
{
}
