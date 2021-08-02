<?php

/**
 * This is the model class for table "comments".
 *
 * The followings are the available columns in table 'comments':
 * @property integer $id
 * @property integer $instance_id
 * @property integer $user_id
 * @property string $comment
 * @property integer $state
 */
class Comments extends BaseActiveRecord
{

        public $stateCaption = "";
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Comments the static model class
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
		return 'comments';
	}

        public function defaultScope()
        {
            return array(
                "condition"=>"state = 2",
                "order"=> "ts DESC"
            );
        }

        public function scopes()
        {
            return array(
                "byDate"=>array(
                    "order"=>"ts DESC",
                ),
                "nonApproved"=>array(
                    "condition"=>"state = 1",
                    "order"=>"ts DESC",
                )
            );
        }

        public function statuses()
        {
            return array(
                "1"=>array(
                    "caption"=>t("На проверке"),
                    "name"=>"unapproved"),
                "2"=>array(
                    "caption"=>t("проверен"),
                    "name"=>"approved"),
                "3"=>array(
                    "caption"=>t("Удален"),
                    "name"=>"deleted"
                )                
                );

        }
        public function getStatus($s)
        {
            $status = $this->statuses();
            return $status[$s];
        }

        /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('instance_id, comment', 'required'),
			array('instance_id, user_id, state', 'numerical', 'integerOnly'=>true),
			array('comment', 'length', 'max'=>2000),
                        array('name', 'length', 'max'=>255),
                        array('comment', 'checkOnEmptyField'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, instance_id, user_id, comment, state, name', 'safe', 'on'=>'search'),
		);
	}

        public function checkOnEmptyField()
        {
            if (empty($this->comment))
            {
                $this->addError("comment",t("Коментарий не может быть пустым"));
                return false;
            }
            return true;
        }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                    'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
                    'instance' => array(self::BELONGS_TO, 'Instances', 'instance_id'),
		);
	}
        // as
        public function ByInstance($instance_id)
        {
            $this->getDbCriteria()->mergeWith(array(
                'condition'=>"instance_id = ".intval($instance_id),
            ));
            return $this;
        }


        public function beforeSave()
        {
            if ($this->isNewRecord) {
                if (Yii::app()->user->id)
                {

                    $this->user_id = Yii::app()->user->id;
                    $this->name = "";

                } else {

                    $this->user_id = null;
                    if (empty($this->name)) {
                        $this->addError("name",t("Введите ваше имя"));
                        return false;
                    }

                }
            }

            return parent::beforeSave();
        
        }
        public function afterFind()
        {

            $state = $this->getStatus($this->state);
            $this->stateCaption = $state["caption"];

            if (!empty($this->user_id))
            {
                $this->name = $this->user->login;
            }

        }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'instance_id' => 'Instance',
			'user_id' => 'User',
			'comment' => 'Комментарий',
			'state' => 'state',
                        'name' => t('Имя'),
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
		$criteria->compare('instance_id',$this->instance_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('state',$this->state);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}