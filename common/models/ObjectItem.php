<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "object_items".
 *
 * @property int $id
 * @property int|null $object_id
 * @property int|null $item_id
 */
class ObjectItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'object_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['object_id', 'item_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'object_id' => 'Object ID',
            'item_id' => 'Category ID',
        ];
    }

    /**
     * @param int $objectId
     * @param array $items
     * @throws Exception
     */
    public static function addObjectItems(int $objectId, array $items): void
    {
        $data = [];

        foreach ($items as $itemId) {
            $data[] = [
                'object_id' => $objectId,
                'item_id' => $itemId
            ];
        }

        Yii::$app->db->createCommand()->batchInsert(self::tableName(), ['object_id', 'item_id'], $data)->execute();
    }

    public static function updateObjectItems(int $objectId, array $items)
    {
        self::deleteAll(['object_id' => $objectId]);
        self::addObjectItems($objectId, $items);
    }
}
