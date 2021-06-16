<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "repairs".
 *
 * @property int $id
 * @property int $receipt
 * @property string $date
 * @property string $client
 * @property string $phone
 * @property string $service_name
 * @property string $equipment
 * @property string $serial_id
 * @property string $facilities
 * @property string $problem
 * @property string $username
 * @property int $money
 * @property string $result_name
 */
class Repairs extends \yii\db\ActiveRecord
{
    public $gallery;
    /**
     * @var mixed|null
     */

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
        return 'repairs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['receipt', 'client', 'phone', 'service_name', 'equipment', 'serial_id', 'facilities', 'problem', 'username', 'result_name'], 'required'],
            [['receipt', 'money'], 'default', 'value' => null],
            [['receipt', 'money'], 'integer'],
            [['date'], 'safe'],
            [['client', 'service_name', 'equipment', 'facilities', 'problem', 'username', 'result_name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 30],
            [['serial_id'], 'string', 'max' => 100],
            [['gallery'], 'file', 'extensions' => 'png, jpg,jpeg', 'maxFiles' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'receipt' => 'Квитанция',
            'date' => 'Дата',
            'client' => 'Клиент',
            'phone' => 'Телефон',
            'service_name' => 'Услуги',
            'equipment' => 'Оборудование',
            'serial_id' => 'Серийный номер',
            'facilities' => 'Комплектация',
            'problem' => 'Неисправность',
            'username' => 'Мастер',
            'money' => 'Цена',
            'result_name' => 'Результат',
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

    /**
     * Gets query for [[ResultName]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResult()
    {
        return $this->hasMany(Results::className(), ['result_name'=>'result']);
    }

    /**
     * Gets query for [[ServiceName]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasMany(Services::className(), ['service_name'=>'service']);
    }

    /**
     * Gets query for [[Username0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMaster()
    {
        return $this->hasMany(Master::className(), ['username'=>'username']);
    }
}
