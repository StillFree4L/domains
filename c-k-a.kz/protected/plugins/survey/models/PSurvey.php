<?php

/**
 * This is the model class for table "p_survey".
 *
 * The followings are the available columns in table 'p_survey':
 * @property integer $id
 * @property string $name
 * @property integer $ts
 * @property integer $active
 *
 * The followings are the available model relations:
 * @property PSurveyVariants[] $pSurveyVariants
 */
class PSurvey extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PSurvey the static model class
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
		return 'p_survey';
	}

        public function defaultScope()
        {
            return array(
                "order"=>"active DESC, ts DESC",
            );
        }

        public function scopes()
        {
            return array(
                "active"=>array(
                    "condition"=>"active = 1",
                )
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
			array('name', 'required'),
			array('ts, active', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>1000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, ts, active', 'safe', 'on'=>'search'),
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
			'pSurveyVariants' => array(self::HAS_MANY, 'PSurveyVariants', 'survey_id'),
                        'votesOverall' => array(self::STAT, "PSurveyUsers", "survey_id")
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => t('Название опроса'),
			'ts' => 'Ts',
			'active' => 'Active',
		);
	}

        public function isVoted()
        {
            $user_id = Yii::app()->user->id;

            if (empty($user_id)) return "3";

            if (PSurveyUsers::model()->exists("survey_id = :survey_id AND user_id = :user_id", array(
                ":survey_id"=>$this->id,
                ":user_id"=>$user_id
            ))) return "1";

            return "2";

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
		$criteria->compare('active',$this->active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}