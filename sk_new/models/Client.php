<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%client}}".
 *
 * @property int $id
 * @property string $client
 * @property string $view
 * @property string|null $img
 * @property string|null $date
 * @property int $created_at
 * @property int $updated_at
 */
class Client extends \yii\db\ActiveRecord
{

  public $image;

  public function behaviors()
  {
      return [
          'image' => [
              'class' => 'rico\yii2images\behaviors\ImageBehave',
          ],
          TimestampBehavior::className(),
      ];
  }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%client}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client', 'view'], 'required'],
            [['date'], 'safe'],
            [['client', 'view'], 'string', 'max' => 255],
            [['image'], 'file', 'extensions' => 'png, jpg,jpeg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Идентификатор'),
            'client' => Yii::t('app', 'Клиент'),
            'view' => Yii::t('app', 'Вид предприятия'),
            'img' => Yii::t('app', 'Логотип'),
            'image' => Yii::t('app', 'Логотип'),
            'date' => Yii::t('app', 'Дата начала партнерства'),
            'created_at' => Yii::t('app', 'Дата добавления'),
            'updated_at' => Yii::t('app', 'Дата изменения'),
        ];
    }
    public function uploadImage(){
        if ($this->validate()) {
            $path = 'images/store/' . $this->image->baseName . '.' . $this->image->extension;
            $this->image->saveAs($path);
            $this->attachImage($path);
            @unlink($path);
            return true;
        }else{
            return false;
        }
    }
}
