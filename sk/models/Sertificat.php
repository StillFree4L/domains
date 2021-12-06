<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "sertificat".
 *
 * @property int $id
 * @property string $name
 * @property string|null $changed_on
 */
class Sertificat extends \yii\db\ActiveRecord
{
    public $gallery;

    public static function tableName()
    {
        return 'sertificat';
    }
    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['gallery'], 'file', 'extensions' => 'png, jpg,jpeg', 'maxFiles' => 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'gallery' => 'Фото',
        ];
    }
    public function uploadGallery(){
        if ($this->validate()) {
            foreach ($this->gallery as $file){
                $path = 'upload/store/' . $file->baseName . '.' . $file->extension;
                $file->saveAs($path);
                $this->attachImage($path);
                @unlink($path);
            }
            return true;
        }else{
            return false;
        }
    }
}
