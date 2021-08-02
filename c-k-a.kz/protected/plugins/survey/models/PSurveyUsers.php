<?php

/**
 * This is the model class for table "p_survey_users".
 *
 * The followings are the available columns in table 'p_survey_users':
 * @property integer $id
 * @property integer $variant_id
 * @property integer $user_id
 * @property integer $survey_id
 * @property integer $ts
 *
 * The followings are the available model relations:
 * @property PSurveyVariants $variant
 * @property Users $user
 * @property PSurvey $survey
 */
class PSurveyUsers extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PSurveyUsers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'p_survey_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('variant_id, user_id, survey_id', 'required'),
			array('variant_id, user_id, survey_id, ts', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, variant_id, user_id, survey_id, ts', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'variant' => array(self::BELONGS_TO, 'PSurveyVariants', 'variant_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'survey' => array(self::BELONGS_TO, 'PSurvey', 'survey_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'variant_id' => 'Variant',
			'user_id' => 'User',
			'survey_id' => 'Survey',
			'ts' => 'Ts',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('variant_id',$this->variant_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('survey_id',$this->survey_id);
		$criteria->compare('ts',$this->ts);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}