<?php

namespace common\models;

use yii\db\ActiveRecord;

class ObjectCategory extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%object_categories}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['object_id', 'category_id'], 'integer'],
        ];
    }
}