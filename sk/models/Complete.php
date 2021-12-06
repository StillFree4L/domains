<?php

namespace app\models;

use Yii;

class Complete extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'complete';
    }

    public function rules()
    {
        return [
            [['name','number','price'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['number','price','repairs_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор',
            'number' => 'Кол-во',
            'price' => 'Цена',
            'repairs_id' => 'Номер заказа',
            'name' => 'Комплектующие',
        ];
    }
}
