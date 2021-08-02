<?php

namespace glob\models;

use Yii;

/**
 * This is the model class for table "spec".
 *
 * @property integer $id
 * @property string $spec
 * @property integer $fak_id
 */
class Spec extends \glob\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spec';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spec', 'fak_id'], 'required'],
            [['fak_id'], 'integer'],
            [['spec'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'spec' => Yii::t('main', 'Spec'),
            'fak_id' => Yii::t('main', 'Fak ID'),
        ];
    }
}
