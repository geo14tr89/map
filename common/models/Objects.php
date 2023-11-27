<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use backend\models\Image;

/**
 * @property integer $id
 * @property float $latitude
 * @property float $longitude
 * @property string $title
 * @property string $description
 * @property string $status
 * @property-read ActiveQuery $images
 * @property-read ActiveQuery $items
 * @property-read ActiveQuery $objectItems
 * @property string $custom_fields
 */
class Objects extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%objects}}';
    }

    /**
     * @param float $north
     * @param float $south
     * @param float $west
     * @param float $east
     * @return array
     */
    public static function getObjectsByPolygon(float $north, float $south, float $west, float $east): array
    {
        return self::find()
            ->joinWith('images')
            ->where(['between', 'latitude', $north, $south])
            ->orWhere(['between', 'longitude', $west, $east])
            ->asArray()
            ->all();
    }

    public static function getObjectsByItemId(int $itemId): array
    {
        return self::find()
            ->joinWith('objectItems')
            ->joinWith('items')
            ->where(['items.id' => $itemId])
            ->asArray()
            ->all();
    }

    /**
     * @return Objects[]
     */
    public static function getObjects(): array
    {
        return self::find()->all();
    }

    /**
     * @param int $id
     * @return Objects
     */
    public static function getObject(int $id): Objects
    {
        return self::find()->joinWith(['items', 'images'])->where([self::tableName() . '.id' => $id])->one();
    }

    /**
     * @return ActiveQuery
     */
    public function getObjectItems(): ActiveQuery
    {
        return $this->hasMany(ObjectItem::class, ['object_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getItems(): ActiveQuery
    {
        return $this->hasMany(Item::class, ['id' => 'item_id'])
            ->via('objectItems');
    }

    public function getImages(): ActiveQuery
    {
        return $this->hasMany(Image::class, ['object_id' => 'id']);
    }
}
