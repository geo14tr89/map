<?php

namespace common\components;

use common\models\Objects;
use common\models\Item;
use yii\base\Component;

class BreadCrumb extends Component
{
    /** @var Item[] */
    public $items = [];

    /** @var array $breadCrumbs */
    public $breadCrumbs = [];

    public function getRelatedData(int $id): void
    {
        $data = Objects::find()
            ->select(
                [
                    Objects::tableName() . '.id',
                    Objects::tableName() . '.title',
                    Item::tableName() . '.id AS item_id',
                    Item::tableName() . '.title AS item_title',
                    Item::tableName() . '.category_id',
                    Item::tableName() . '.parent_id',
                ]
            )
            ->joinWith('objectItems')
            ->joinWith('items', true, 'RIGHT JOIN')
            ->where([Objects::tableName() . '.id' => $id])
            ->one();

        if (!empty($data->items)) {
            $this->items = $data->items;
        }
    }

    public function prepareData(): void
    {
        foreach ($this->items as $item) {
            if ($item->parent_id === null) {
                $this->breadCrumbs[$item->category_id][] = $item;
            } else {
                $this->breadCrumbs[$item->parent_id][] = $item;
            }
        }
    }

    public function getBreadCrumbsByObjectId(int $id): array
    {
        $this->getRelatedData($id);
        $this->prepareData();

        return array_values($this->breadCrumbs);
    }
}
