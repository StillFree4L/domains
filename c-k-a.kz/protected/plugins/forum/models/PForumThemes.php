<?php

/**
 * This is the model class for table "p_forum_themes".
 *
 * The followings are the available columns in table 'p_forum_themes':
 * @property integer $id
 * @property string $name
 * @property string $post
 * @property integer $category_id
 * @property integer $author_id
 * @property integer $ts
 * @property integer $state
 *
 * The followings are the available model relations:
 * @property PForumPosts[] $pForumPosts
 * @property PForumThemeViews[] $pForumThemeViews
 * @property Users $author
 * @property PForumCategories $category
 */
class PForumThemes extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PForumThemes the static model class
	 */

        var $post = "";

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'p_forum_themes';
	}

        public function defaultScope()
        {
            return array(
                "alias"=>"th",
                "condition"=>"th.state != 3",
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
			array('name, category_id', 'required'),
			array('category_id, author_id, ts, state, last_post_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>1000),
			array('post', 'length', 'max'=>2000),
                        array('post', 'checkOnEmptyField'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, post, category_id, author_id, ts, state', 'safe', 'on'=>'search'),
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
			'pForumPosts' => array(self::HAS_MANY, 'PForumPosts', 'theme_id'),
			'pForumThemeViews' => array(self::HAS_MANY, 'PForumThemeViews', 'theme_id'),
			'author' => array(self::BELONGS_TO, 'Users', 'author_id'),
			'category' => array(self::BELONGS_TO, 'PForumCategories', 'category_id'),
                        'pPostCount' => array(self::STAT, 'PForumPosts','theme_id'),
                        'pViewCount' => array(self::STAT, 'PForumThemeViews','theme_id'),
                        'lastPost' => array(self::BELONGS_TO, 'PForumPosts', 'last_post_id'),
		);
	}

        public function checkOnEmptyField()
        {
            if ($this->category->type != "1") return false;
            if (empty($this->post) AND $this->isNewRecord)
            {
                $this->addError("post",t("Сообщение не может быть пустым"));
                return false;
            }
            return true;
        }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => t('Заголовок темы'),
			'post' => t('Сообщение'),
			'category_id' => 'Category',
			'author_id' => 'Author',
			'ts' => 'Ts',
			'state' => 'State',
                        'last_post_id' => 'last_post'
		);
	}

        public function beforeSave()
        {
            if ($this->isNewRecord)
            {
                $this->author_id = Yii::app()->user->id;
            }
            return parent::beforeSave();
        }

        public function afterSave()
        {

            parent::afterSave();

            if ($this->isNewRecord)
            {
                $post = new PForumPosts();
                $post->theme_id = $this->id;
                $post->post = $this->post;

                if ($post->validate() AND $post->save())
                {
                    
                } else {
                    $this->delete();
                }

            }            

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
		$criteria->compare('post',$this->post,true);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('author_id',$this->author_id);
		$criteria->compare('ts',$this->ts);
		$criteria->compare('state',$this->state);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}