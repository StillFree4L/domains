<?php

/**
 * This is the model class for table "menu_groups".
 *
 * The followings are the available columns in table 'menu_groups':
 * @property integer $id
 * @property string $uniq_name
 * @property integer $is_deletable
 * @property string $caption
 *
 * The followings are the available model relations:
 * @property Menu[] $menus
 */
class MenuGroups extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MenuGroups the static model class
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
		return 'menu_groups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('caption, uniq_name', 'required'),
			array('is_deletable', 'numerical', 'integerOnly'=>true),
			array('caption, uniq_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, is_deletable, caption, uniq_name', 'safe', 'on'=>'search'),
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
			'menus' => array(self::HAS_MANY, 'Menu', 'group_id'),
		);
	}

        public function defaultScope()
        {
            return array(
                "order"=>"id"
            );
        }

        public function beforeDelete()
        {
                
            if (!$this->is_deletable)
            {
                return false;
            }
            return parent::beforeDelete();
        }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',			
			'is_deletable' => 'Is Deletable',
			'caption' => t('Название'),
                        'uniq_name' => t("Уникальный идентификатор")
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
		$criteria->compare('is_deletable',$this->is_deletable);
		$criteria->compare('caption',$this->caption,true);
                $criteria->compare('uniq_name',$this->uniq_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}