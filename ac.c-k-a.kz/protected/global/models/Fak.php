<?php

namespace glob\models;

use Yii;

/**
 * This is the model class for table "fak".
 *
 * @property integer $id
 * @property string $fak
 */
class Fak extends \glob\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fak';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fak'], 'required'],
            [['fak'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'fak' => Yii::t('main', 'Fak'),
        ];
    }
}
