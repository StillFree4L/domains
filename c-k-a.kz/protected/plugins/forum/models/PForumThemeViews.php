<?php

/**
 * This is the model class for table "p_forum_theme_views".
 *
 * The followings are the available columns in table 'p_forum_theme_views':
 * @property integer $id
 * @property integer $user_id
 * @property integer $theme_id
 * @property integer $ts
 *
 * The followings are the available model relations:
 * @property PForumThemes $theme
 */
class PForumThemeViews extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PForumThemeViews the static model class
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
		return 'p_forum_theme_views';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, theme_id', 'required'),
			array('user_id, theme_id, ts', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, theme_id, ts', 'safe', 'on'=>'search'),
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
			'theme' => array(self::BELONGS_TO, 'PForumThemes', 'theme_id'),
                        'user' => array(self::BELONGS_TO, 'PForumUsers', 'user_id')
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
			'theme_id' => 'Theme',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('theme_id',$this->theme_id);
		$criteria->compare('ts',$this->ts);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}