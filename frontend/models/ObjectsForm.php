<?php

namespace frontend\models;

use common\components\Config;
use yii\base\Model;
use yii\db\ActiveQuery;

class ObjectsForm extends Model
{
    public $north;
    public $south;
    public $west;
    public $east;
    public $building_type = null;
    public $architectural_style = null;
    public $architectural_substyle = null;
    public $architect = null;
    public $building_subtype = null;

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
            ['architectural_style', 'safe'],
            ['architect', 'safe'],
            ['building_subtype', 'safe'],
        ];
    }

    /**
     * @param ActiveQuery $query
     * @return ActiveQuery
     */
    public function filter(ActiveQuery $query): ActiveQuery
    {
        $categoriesConfig = array_column(Config::getCategoriesConfig(), 'key');

        foreach ($categoriesConfig as $category) {
            if ($this->$category !== null) {
                $clientValuesArray = explode(',', $this->$category);
                if (count($clientValuesArray) > 1) {
                    foreach ($clientValuesArray as $key => $type) {
                        if ($key === 0) {
                            $query->andWhere("JSON_EXTRACT(custom_fields, '$.{$category}') = '{$type}'");
                        } else {
                            $query->orWhere("JSON_EXTRACT(custom_fields, '$.{$category}') = '{$type}'");
                        }
                    }
                } else {
                    $query->andWhere("JSON_EXTRACT(custom_fields, '$.{$category}') = '{$clientValuesArray[0]}'");
                }
            }
        }

        return $query;
    }
}