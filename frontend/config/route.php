<?php
$routes = [];

foreach (glob(__DIR__ . "/route/*.php") as $filename) {
    $routes = \yii\helpers\ArrayHelper::merge(require($filename), $routes);
}

return $routes;
