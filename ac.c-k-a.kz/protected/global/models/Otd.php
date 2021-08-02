<?php

namespace glob\models;

use Yii;

/**
 * This is the model class for table "otd".
 *
 * @property integer $id
 * @property string $otd
 */
class Otd extends \glob\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'otd';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['otd'], 'required'],
            [['otd'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'otd' => Yii::t('main', 'Otd'),
        ];
    }
}
