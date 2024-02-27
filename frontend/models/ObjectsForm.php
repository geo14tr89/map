<?php

namespace frontend\models;

use yii\base\Model;
use yii\db\ActiveQuery;

class ObjectsForm extends Model
{
    public $north;
    public $south;
    public $west;
    public $east;
    public $building_type = null;
    public $architect = null;
    public $architectural_style = null;

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            ['north', 'required'],
            ['south', 'required'],
            ['west', 'required'],
            ['east', 'required'],
            ['building_type', 'safe'],
            ['architect', 'safe'],
            ['architectural_style', 'safe'],
        ];
    }

    /**
     * @param ActiveQuery $query
     * @return ActiveQuery
     */
    public function filter(ActiveQuery $query): ActiveQuery
    {
        if ($this->building_type !== null) {
            $buildingTypeArray = explode(',', $this->building_type);
            if (count($buildingTypeArray) > 1) {
                foreach ($buildingTypeArray as $type) {
                    $query->orWhere("JSON_EXTRACT(custom_fields, '$.building_type') = '{$type}'");
                }
            } else {
                $query->andWhere("JSON_EXTRACT(custom_fields, '$.building_type') = '{$buildingTypeArray[0]}'");
            }
        }
        if ($this->architect !== null) {
            $query->andWhere("JSON_EXTRACT(custom_fields, '$.architect') = '{$this->architect}'");
        }
        if ($this->architectural_style !== null) {
            $query->andWhere("JSON_EXTRACT(custom_fields, '$.architectural_style') = '{$this->architectural_style}'");
        }

        return $query;
    }
}