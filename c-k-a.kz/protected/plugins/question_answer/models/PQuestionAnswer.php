<?php

/**
 * This is the model class for table "p_question_answer".
 *
 * The followings are the available columns in table 'p_question_answer':
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $question
 * @property string $answer
 * @property integer $ts
 */
class PQuestionAnswer extends BaseActiveRecord
{

        var $stateCaption = "";
        var $escapedAnswer = "";
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PQuestionAnswer the static model class
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
		return 'p_question_answer';
	}

        public function defaultScope()
        {
            return array(
                "order" => "ts DESC",
                "condition"=>"state != 3",
            );
        }

        public function scopes()
        {
            return array(
                "approved" => array(
                    "condition"=>"state = 2",
                ),
                "nonApproved" => array(
                    "condition"=>"state = 1",
                )
            );
        }

        public function instanceStates()
        {
            return
                array("1"=>t("Ожидает"),
                    "2"=>t("Утвержден"),
                    "3"=>t("Удален"));
        }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, question', 'required'),
			array('ts', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>1000),
			array('email', 'length', 'max'=>255),
			array('question, answer', 'length', 'max'=>3000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, email, question, answer, ts', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => t('Ваше имя'),
			'email' => t('Адрес электронной почты'),
			'question' => t('Вопрос'),
			'answer' => t('Ответ'),
			'ts' => 'Ts',
		);
	}

        public function beforeSave()
        {

            if (!empty($this->answer) AND $this->state == 1)
            {
                $this->state = 2;
            }

            return parent::beforeSave();
        }

        public function afterFind()
        {

            $states = $this->instanceStates();

            $this->stateCaption = $states[$this->state];
            $this->escapedAnswer = addslashes($this->answer);

            parent::afterFind();
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
		$criteria->compare('email',$this->email,true);
		$criteria->compare('question',$this->question,true);
		$criteria->compare('answer',$this->answer,true);
		$criteria->compare('ts',$this->ts);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}