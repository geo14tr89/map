<?php

namespace frontend\models;

use backend\models\Image;
use common\models\Objects;
use JsonSerializable;

class ObjectResponse implements JsonSerializable
{
    /** @var Objects $mapObject */
    private $mapObject;

    /** @var Image $image */
    private $image;

    public function __construct(Objects $mapObject, Image $image = null)
    {
        $this->mapObject = $mapObject;
        $this->image = $image;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->mapObject->id,
            'title' => $this->mapObject->title,
            'description' => $this->mapObject->description,
            'latitude' => $this->mapObject->latitude,
            'longitude' => $this->mapObject->longitude,
            'custom_fields' => $this->mapObject->custom_fields,
            'image' => ($this->image !== null) ? $this->image->toArray() : null
        ];
    }
}
