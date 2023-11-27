<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\web\UploadedFile;
use yii\imagine\Image as ImagineLib;

/**
 * This is the model class for table "images".
 *
 * @property integer $id
 * @property string $full_url
 * @property string $preview_url
 * @property integer $object_id
 * @property integer $status
 * @property string $description
 * @property string $title
 */
class Image extends ActiveRecord
{
    public const IMAGE_PATH_FULL = '/images/';
    public const IMAGE_PATH_THUMB = '/thumb/';
    public const OBJECT_DIR = 'object_';

    public const STATUS_PRE_MODERATE = 1;
    public const STATUS_PUBLISHED = 2;
    public const STATUS_IMAGE_MAP = [
        self::STATUS_PRE_MODERATE => 'Pre moderate',
        self::STATUS_PUBLISHED => 'Published',
    ];

    public const THUMB_IMAGE_WIDTH = 150;
    public const THUMB_IMAGE_HEIGHT = 150;

    /**
     * @var mixed
     */
    public $image_file;

    /** @var string $uploadDir */
    public $uploadDir;

    /** @var UploadedFile $file */
    public $file;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'images';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['object_id', 'status'], 'integer'],
            [['preview_url', 'full_url', 'description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['image_file'], 'image', 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'object_id' => 'Object ID',
            'preview_url' => 'Preview Url',
            'full_url' => 'Full Url',
            'title' => 'Title',
            'description' => 'Description',
            'image_file' => 'Image File',
            'status' => 'Status'
        ];
    }

    /**
     * @return bool
     */
    public function upload(): bool
    {
        if ($this->validate()) {
            $uploadDir = Yii::getAlias('@frontendRoot') . self::IMAGE_PATH_FULL;

            $this->createObjectDirectories($uploadDir);
            $file = UploadedFile::getInstance($this, 'image_file');

            $fullFileName = $uploadDir . self::OBJECT_DIR . $this->object_id . '/' .
                $file->baseName . '.' . $file->extension;
            $thumbFileName = $uploadDir . self::OBJECT_DIR . $this->object_id .
                self::IMAGE_PATH_THUMB . $file->baseName . '.' . $file->extension;

            $this->full_url = self::IMAGE_PATH_FULL . self::OBJECT_DIR . $this->object_id . '/' .
                $file->baseName . '.' . $file->extension;
            $this->preview_url = self::IMAGE_PATH_FULL . self::OBJECT_DIR . $this->object_id .
                self::IMAGE_PATH_THUMB . $file->baseName . '.' . $file->extension;

            $this->status = self::STATUS_PRE_MODERATE;

            if ($file->saveAs($fullFileName) === true) {
                ImagineLib::thumbnail($fullFileName, self::THUMB_IMAGE_WIDTH, self::THUMB_IMAGE_HEIGHT)
                    ->save($thumbFileName, ['quality' => 80]);
            }
        }

        return false;
    }

    protected function createObjectDirectories(string $uploadDir)
    {
        if (!mkdir($concurrentDirectory = $uploadDir . self::OBJECT_DIR . $this->object_id) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        if (!mkdir($concurrentDirectory = $uploadDir . self::OBJECT_DIR . $this->object_id . self::IMAGE_PATH_THUMB) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Thumb directory "%s" was not created', $concurrentDirectory));
        }
    }

    protected function prepareFullFileName(): string
    {
        return $this->uploadDir . self::OBJECT_DIR . $this->object_id . '/' .
            $this->file->baseName . '.' . $this->file->extension;
    }

    protected function prepareThumbFileName(): string
    {
        return $this->uploadDir . self::OBJECT_DIR . $this->object_id .
            self::IMAGE_PATH_THUMB . $this->file->baseName . '.' . $this->file->extension;
    }

    protected function setImageAttributes($objectId): void
    {
        $this->object_id = $objectId;
        $this->full_url = self::IMAGE_PATH_FULL . self::OBJECT_DIR . $objectId . '/' .
            $this->file->baseName . '.' . $this->file->extension;
        $this->preview_url = self::IMAGE_PATH_FULL . self::OBJECT_DIR . $objectId .
            self::IMAGE_PATH_THUMB . $this->file->baseName . '.' . $this->file->extension;
        $this->status = self::STATUS_PRE_MODERATE;
    }
}
