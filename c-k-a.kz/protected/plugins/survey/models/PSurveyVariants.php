<?php

/**
 * This is the model class for table "p_survey_variants".
 *
 * The followings are the available columns in table 'p_survey_variants':
 * @property integer $id
 * @property string $name
 * @property integer $ts
 * @property integer $survey_id
 *
 * The followings are the available model relations:
 * @property PSurveyUsers[] $pSurveyUsers
 * @property PSurvey $survey
 */
class PSurveyVariants extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PSurveyVariants the static model class
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
		return 'p_survey_variants';
	}

        public function defaultScope()
        {
            return array(
                "order"=>"ts"
            );
        }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, survey_id', 'required'),
			array('ts, survey_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>1000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, ts, survey_id', 'safe', 'on'=>'search'),
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
			'pSurveyUsers' => array(self::HAS_MANY, 'PSurveyUsers', 'variant_id'),
                        'votesCount' => array(self::STAT, "PSurveyUsers", "variant_id"),
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
			'name' => 'Name',
			'ts' => 'Ts',
			'survey_id' => 'Survey',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('ts',$this->ts);
		$criteria->compare('survey_id',$this->survey_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}