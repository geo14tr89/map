<?php

namespace frontend\models;

use common\models\Item;
use common\models\Objects;

class SingleObjectResponse implements \JsonSerializable
{
    /** @var Objects $mapObject */
    private $mapObject;

    /** @var Item[] $items */
    private $items;

    /** @var array $breadCrumbs */
    private $breadCrumbs;

    public function __construct(Objects $mapObject, array $breadCrumbs)
    {
        $this->mapObject = $mapObject;
        $this->breadCrumbs = $breadCrumbs;
        $this->prepareResponse();
    }

    private function prepareResponse()
    {
        unset($this->mapObject->objectItems);

        foreach ($this->mapObject->items as $item) {
            $this->items[] = [
                'id' => $item->id,
                'name' => $item->title,
                'category_id' => $item->category_id
            ];
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->mapObject->id,
            'latitude' => $this->mapObject->latitude,
            'longitude' => $this->mapObject->longitude,
            'title' => $this->mapObject->title,
            'description' => $this->mapObject->description,
            'status' => $this->mapObject->status,
            'custom_fields' => $this->mapObject->custom_fields,
            'items' => $this->items,
            'images' => $this->mapObject->images,
            'categories' => $this->breadCrumbs
        ];
    }
}
