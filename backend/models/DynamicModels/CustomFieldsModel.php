<?php

namespace backend\models\DynamicModels;

use yii\base\DynamicModel;

class CustomFieldsModel extends DynamicModel
{
    public $date_built = null;
    public $building_type= null;
    public $architect = null;
    public $architectural_style = null;
    public $architectural_substyle = null;
    public $building_subtype = null;

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['date_built', 'building_type', 'architect', 'architectural_style',
                'architectural_substyle', 'building_subtype'], 'safe']
        ];
    }

    /**
     * @return string[]
     */
    public function getDefaultStructure(): array
    {
        return [
            'date_built' => '',
            'building_type' => '',
            'architect' => '',
            'architectural_style' => '',
            'architectural_substyle' => '',
            'building_subtype' => ''
        ];
    }
}