<?php

namespace glob\models;

use Yii;

/**
 * This is the model class for table "form".
 *
 * @property integer $id
 * @property string $form
 */
class Form extends \glob\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'form';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form'], 'required'],
            [['form'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'form' => Yii::t('main', 'Form'),
        ];
    }
}
