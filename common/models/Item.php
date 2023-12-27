<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "items".
 *
 * @property int $id
 * @property int|null $category_id
 * @property int|null $parent_id
 * @property string|null $title
 * @property string|null $description
 * @property-read ActiveQuery $objectItems
 * @property-read ActiveQuery $objects
 */
class Item extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'parent_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category',
            'parent_id' => 'Parent ID',
            'title' => 'Title',
            'description' => 'Description',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'category_id',
            'title',
            'description'
        ];
    }

    public static function getItemById(int $id): array
    {
        return self::find()
            ->joinWith('objectItems')
            ->joinWith('objects')
            ->where(['items.id' => $id])
            ->asArray()
            ->all();
    }

    /**
     * @return ActiveQuery
     */
    public function getObjectItems(): ActiveQuery
    {
        return $this->hasMany(ObjectItem::class, ['item_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getObjects(): ActiveQuery
    {
        return $this->hasMany(Objects::class, ['id' => 'object_id'])
            ->via('objectItems');
    }
}
