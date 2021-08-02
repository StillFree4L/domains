<?php

/**
 * This is the model class for table "plugins".
 *
 * The followings are the available columns in table 'plugins':
 * @property integer $id
 * @property string $name
 * @property integer $ts
 */
class Plugins extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Plugins the static model class
	 */
        var $init = null;
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'plugins';
	}

        public function byName($uniq_name)
        {
            $this->getDbCriteria()->mergeWith(array(
                'condition'=>"uniq_name = '".mysql_escape_string($uniq_name)."'",
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
			array('name, ts, uniq_name', 'required'),
			array('ts', 'numerical', 'integerOnly'=>true),
			array('name, uniq_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, uniq_name, ts', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'ts' => 'Ts',
                        'uniq_name' => 'Uniq_name'
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
                $criteria->compare('uniq_name',$this->uniq_name);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}