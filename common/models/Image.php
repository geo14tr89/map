<?php

namespace common\models;

use Yii;
use yii\db\Exception;
use yii\imagine\Image as ImagineLib;
use yii\web\UploadedFile;

class Image extends \backend\models\Image
{

    /**
     * @param UploadedFile $file
     * @param $objectId
     * @throws Exception
     */
    public function uploadExternalFile(UploadedFile $file, $objectId): void
    {
        $this->uploadDir = Yii::getAlias('@frontendRoot') . self::IMAGE_PATH_FULL;
        $this->file = $file;
        $this->setImageAttributes($objectId);
        $this->createObjectDirectories($this->uploadDir);
        $fullFileName = $this->prepareFullFileName();
        $thumbFileName = $this->prepareThumbFileName();

        if ($file->saveAs($fullFileName) === true) {
            ImagineLib::thumbnail($fullFileName, self::THUMB_IMAGE_WIDTH, self::THUMB_IMAGE_HEIGHT)
                ->save($thumbFileName, ['quality' => 80]);

            if ($this->save() === false) {
                throw new Exception('Error per saving external image');
            }
        }
    }
}
