<?php

/**
 * This is the model class for table "p_forum_posts".
 *
 * The followings are the available columns in table 'p_forum_posts':
 * @property integer $id
 * @property string $post
 * @property integer $author_id
 * @property integer $theme_id
 * @property integer $ts
 * @property integer $state
 * @property integer $last_time_edited
 *
 * The followings are the available model relations:
 * @property PSurveyUsers $author
 * @property PForumThemes $theme
 */
class PForumPosts extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PForumPosts the static model class
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
		return 'p_forum_posts';
	}

        public function defaultScope()
        {
            return array(
                "alias"=>"p",
                "order"=>"p.ts"
            );
        }

        public function scopes()
        {
            return array(
                "lastMessage"=>array(
                    "select"=>array("p.author_id","p.ts"),
                    "alias"=>"p",
                    "order"=>"p.ts DESC",
                    "limit"=>"1"
                ),
                "lastMessageFull"=>array(
                    "alias"=>"p",
                    "order"=>"p.ts DESC",
                    "limit"=>"1"
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
			array('theme_id', 'required'),
			array('author_id, theme_id, ts, state, last_time_edited', 'numerical', 'integerOnly'=>true),
			array('post', 'length', 'max'=>2000),
                        array('ts', 'checkOnRetryPost'),
                        array('post', 'checkOnEmptyTags'),
                        array('theme_id','checkOnValidTheme'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, post, author_id, theme_id, ts, state, last_time_edited', 'safe', 'on'=>'search'),
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
			'author' => array(self::BELONGS_TO, 'PForumUsers', 'author_id'),
			'theme' => array(self::BELONGS_TO, 'PForumThemes', 'theme_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'post' => 'Post',
			'author_id' => 'Author',
			'theme_id' => 'Theme',
			'ts' => 'Ts',
			'state' => 'State',
			'last_time_edited' => 'Last Time Edited',
		);
	}

        public function checkOnValidTheme()
        {
            if ($this->theme->state == '3') return false;
        }

        public function checkOnRetryPost()
        {
            if ($this->isNewRecord) {
                $l = PForumPosts::model()->resetScope()->lastMessageFull()->findByAttributes(array(
                    "theme_id"=>$this->theme_id,
                    "author_id"=>$this->author_id
                ));

                if ($l->ts >= time()-1)
                {
                    $this->addError("post",t("Сообщения можно отправлять не чаще чем раз в 15 секунд"));                   

                    return false;
                }

            }
            return true;
        }
        
        public function afterSave()
        {
            if ($this->isNewRecord)
            {
                PForumThemes::model()->updateByPk($this->theme_id, array(
                    "last_post_id"=>$this->id,
                ));
            }
            return parent::afterSave();
        }

        public function beforeValidate()
        {
            if (empty(Yii::app()->user->id)) return false;
            
            if ($this->isNewRecord)
            {
                $this->author_id = Yii::app()->user->id;
            }
            return parent::beforeValidate();
        }
        
        public function checkOnEmptyTags()
        {
            $pattern = "/<p[^>]*>[\s|&nbsp;]*<\/p>/"; 
            $this->post = preg_replace($pattern, '', $this->post); 
            return true;
        }

        public function afterFind()
        {

            $this->post = str_replace("[quote]","<div class='quote'>",$this->post);
            $this->post = str_replace("[/quote]","</div>",$this->post);
            $this->post = str_replace("[quote_author]","<div class='quote_author'>",$this->post);
            $this->post = str_replace("[/quote_author]","</div>",$this->post);
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
		$criteria->compare('post',$this->post,true);
		$criteria->compare('author_id',$this->author_id);
		$criteria->compare('theme_id',$this->theme_id);
		$criteria->compare('ts',$this->ts);
		$criteria->compare('state',$this->state);
		$criteria->compare('last_time_edited',$this->last_time_edited);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}