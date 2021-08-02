<?php

namespace glob\models;

use glob\components\ActiveRecord;
use Yii;

/**
 * This is the model class for table "grup".
 *
 * @property integer $id
 * @property string $grup
 * @property integer $fak
 * @property integer $form
 * @property integer $otd
 * @property integer $spec
 * @property integer $course
 * @property integer $changed_course
 * @property integer $has_practice
 * @property integer $show
 */
class Grup extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grup', 'fak', 'course', 'changed_course'], 'required'],
            [['fak', 'form', 'otd', 'spec', 'course', 'changed_course', 'has_practice', 'show'], 'integer'],
            [['grup'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'grup' => Yii::t('main', 'Grup'),
            'fak' => Yii::t('main', 'Fak'),
            'form' => Yii::t('main', 'Form'),
            'otd' => Yii::t('main', 'Otd'),
            'spec' => Yii::t('main', 'Spec'),
            'course' => Yii::t('main', 'Course'),
            'changed_course' => Yii::t('main', 'Changed Course'),
            'has_practice' => Yii::t('main', 'Has Practice'),
            'show' => Yii::t('main', 'Show'),
        ];
    }

    public function getFakR()
    {
        return $this->hasOne(Fak::className(), ["id"=>"fak"]);
    }

    public function getOtdR()
    {
        return $this->hasOne(Otd::className(), ["id"=>"otd"]);
    }

    public function getFormR()
    {
        return $this->hasOne(Form::className(), ["id"=>"form"]);
    }

    public function getSpecR()
    {
        return $this->hasOne(Spec::className(), ["id"=>"spec"]);
    }




}
