<?php

namespace app\models;

use Yii;

class Clients extends \yii\db\ActiveRecord
{
    public $gallery;

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
    public static function tableName()
    {
        return 'clients';
    }

    public function rules()
    {
        return [
            [['client'], 'required'],
            [['client'], 'string', 'max' => 255],
            [['client'], 'unique'],
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
            'client' => 'Клиент',
            'updated_at'=>'Дата изменения',
            'created_at'=>'Дата создания',
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
