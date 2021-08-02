<?php

namespace glob\models;

use glob\components\ActiveRecord;
use Yii;

/**
 * This is the model class for table "dis".
 *
 * @property integer $id
 * @property string $dis
 * @property string $tea
 * @property integer $credits
 * @property integer $position
 * @property integer $gos
 * @property integer $kurs
 * @property integer $gek
 *
 */
class Dis extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dis', 'tea'], 'required'],
            [['credits', 'position'], 'integer'],
            [['kurs','gos','gek'],'safe'],
            [['dis'], 'string', 'max' => 100],
            [['tea'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'dis' => Yii::t('main', 'Dis'),
            'tea' => Yii::t('main', 'Tea'),
            'credits' => Yii::t('main', 'Credits'),
            'position' => Yii::t('main', 'Position'),
            'gos' => Yii::t('main', 'Gos'),
            'kurs' => Yii::t('main', 'Kurs'),
            'gek' => Yii::t('main', 'Gek'),
        ];
    }

}
