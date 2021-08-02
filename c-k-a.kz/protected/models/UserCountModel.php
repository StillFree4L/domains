<?php

/**
 * This is the model class for table "user_count".
 *
 * The followings are the available columns in table 'user_count':
 * @property integer $id
 * @property integer $user_id
 * @property string $url
 * @property integer $ts
 * @property string $session_id
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UserCountModel extends BaseActiveRecord
{
    
        var $count = 0;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserCountModel the static model class
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
		return 'user_count';
	}

        public function scopes()
        {
            return array(
                "uniqueUsers"=>array(
                    "select"=>"count(DISTINCT user_id) as count",                    
                ),
                "uniqueGuests"=>array(
                    "distinct"=>true,
                    "select"=>"count(DISTINCT session_id) as count",    
                ),                
            );
        }
        
        /**
         * SCOPES WITH PARAMS
         */
        public function monthly($m)
        {
            
            $y = date('Y');
                
            if ($m == 12) 
            {
                $ny = $y+1;
                $nm = $m;
            } else {
                $nm = $m+1;
                $ny = $y;
            }
            
            $this->getDbCriteria()->mergeWith(array(                
                "select"=>"DATE_FORMAT(FROM_UNIXTIME(ts), '%d.%m.%Y') as ts",
                "condition"=>"ts >= ".(strtotime("1.".$m.".".$y)." AND ts < ".strtotime("1.".$nm.".".$ny)),
                "group"=>"DATE_FORMAT(FROM_UNIXTIME(ts), '%d.%m.%Y')"
            ));
            return $this;
        }
        public function yearly($y)
        {
            $this->getDbCriteria()->mergeWith(array(   
                "select"=>"DATE_FORMAT(FROM_UNIXTIME(ts), '%m.%Y') as ts",
                "condition"=>"ts >= ".(strtotime("1.1.".$y)." AND ts < ".strtotime("1.1.".($y+1))),
                "group"=>"DATE_FORMAT(FROM_UNIXTIME(ts), '%m.%Y')"
            ));
            return $this;
        }
        
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, ts', 'numerical', 'integerOnly'=>true),
			array('url', 'length', 'max'=>500),
			array('session_id', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, url, ts, session_id', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
                    
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'url' => 'Url',
			'ts' => 'Ts',
			'session_id' => 'Session',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('ts',$this->ts);
		$criteria->compare('session_id',$this->session_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}