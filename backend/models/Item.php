<?php

namespace backend\models;

use common\components\Config;
use yii\db\Exception;

class Item extends \common\models\Item
{
    /**
     * @param int $categoryId
     * @return array
     */
    public static function menuLinks(int $categoryId): array
    {
        $links = [];
        $items = \common\models\Item::find()->where(['category_id' => $categoryId])->all();

        /** @var \common\models\Item $item */
        foreach ($items as $item) {
            $links[] = [
                'label' => $item->title,
                'url' => ['/item/index/?category_id=' . $item->id],
            ];
        }

        return $links;
    }

    /**
     * @param int $categoryId
     * @return array
     */
    public static function getCategories(int $categoryId): array
    {
        $parentId = Config::getCategory($categoryId)['parent_id'];

        return self::find()->where(['category_id' => $parentId])->asArray()->all();
    }
}
