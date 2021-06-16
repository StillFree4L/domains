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
    public $imageFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sertificat';
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['changed_on'],
                ],
                // если вместо метки времени UNIX используется datetime:
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['changed_on'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'extensions' => 'png, jpg,jpeg'],
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
            'changed_on' => 'Changed On',
            'imageFile'=>'Фото',
        ];
    }
    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('img/sertificat/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            $this->name=$this->imageFile;
            return true;
        } else {
            return false;
        }
    }
}
