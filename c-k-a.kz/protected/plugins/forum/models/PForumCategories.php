<?php

/**
 * This is the model class for table "p_forum_categories".
 *
 * The followings are the available columns in table 'p_forum_categories':
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $ts
 *
 * The followings are the available model relations:
 * @property PForumCategories $parent
 * @property PForumCategories[] $pForumCategories
 * @property PForumThemes[] $pForumThemes
 */
class PForumCategories extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PForumCategories the static model class
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
		return 'p_forum_categories';
	}

        public function scopes()
        {
            return array(
                "top"=>array(
                    "condition"=>"parent_id IS NULL"
                ),
                "byName"=>array(
                    "order"=>"name"
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
			array('parent_id, ts, type, can_add_themes', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>1000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, parent_id, ts', 'safe', 'on'=>'search'),
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
			'parent' => array(self::BELONGS_TO, 'PForumCategories', 'parent_id'),
			'pForumCategories' => array(self::HAS_MANY, 'PForumCategories', 'parent_id'),
			'pForumThemes' => array(self::HAS_MANY, 'PForumThemes', 'category_id'),
                        "pCategoryChilds" => array(self::HAS_MANY, 'PForumCategories', "parent_id"),
                        'pTCount'=>array(self::STAT,"PForumThemes","category_id")
		);
	}
        
        public function pThemesCount()
        {
            $count = 0;
            if (!empty($this->pCategoryChilds))
            {
                foreach ($this->pCategoryChilds as $child)
                {
                    $count += $child->PThemesCount();
                }
            }
            $count += $this->pTCount;
            
            return $count;
        }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => t('Наименование'),
			'parent_id' => 'Parent',
			'ts' => 'Ts',
                        'type' => t('Тип'),
                        'can_add_themes'=>t("Пользователи могут добавлять темы")
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
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('ts',$this->ts);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}