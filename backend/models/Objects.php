<?php

namespace backend\models;

use yii\helpers\ArrayHelper;

class Objects extends \common\models\Objects
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['latitude', 'longitude'], 'double'],
            [['title', 'description'], 'string'],
        ];
    }

    public static function getNameObjects()
    {
        return ArrayHelper::map(self::getObjects(), 'id', 'title');
    }
}
