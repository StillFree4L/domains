<?php

/**
 * This is the model class for table "instances".
 *
 * The followings are the available columns in table 'instances':
 * @property integer $id
 * @property string $caption
 * @property string $preview
 * @property string $body
 * @property integer $type
 * @property integer $state
 * @property integer $ts
 * @property integer $is_c
 */
class Instances extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Instances the static model class
	 */
        protected $multilang = true;

        public $stateCaption = "";
        public $typeCaption = "";
        public $parentCategories = array();
        public $parentCategoriesCaptions = array();
        public $search_string;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return parent::tableName('instances');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, state, ts, is_c, owner_id', 'numerical', 'integerOnly'=>true),
			array('caption', 'length', 'max'=>1000),
			array('preview', 'length', 'max'=>2000),
                        array('preview, body', 'checkOnEmptyTags'),
                        array('label, target', 'length', 'max'=>255),
                        array('caption', 'checkOnEmptyField'),
			array('body, label, ref', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, caption, preview, body, type, state, ts, is_c, owner_id, label, ref, target', 'safe', 'on'=>'search'),
		);
	}

        public function defaultScope()
        {
            return array(
                        "condition" => "state = 2",
                        "order"=>"ts DESC"
                    );
        }

        public function scopes()
        {
            return array(
                'categories'=>array(
                    'condition'=>'type=1',
                    'order'=>'state, caption'
                ),
                'records'=>array(
                    'condition'=>'type=2',
                    'order'=>'state, caption'
                ),
                'pages'=>array(
                    'condition'=>'type=3',
                    'order'=>'state, caption'
                ),
                "linkCategories"=>array(
                    "condition"=>"type=4",
                    "order"=>"state, caption",
                ),
                "links"=>array(
                    "condition"=>"type=5",
                    "order"=>"state, caption"
                )
            );
        }

        /**
         * SCOPES WITH PARAMS
         */
        public function byLabel($label)
        {
            $this->getDbCriteria()->mergeWith(array(
                'condition'=>"label = '".mysql_escape_string($label)."'",
            ));
            return $this;
        }

        // RULES
        public function checkOnEmptyField()
        {
            if (empty($this->caption))
            {
                $this->addError("caption",t("Заголовок не может быть пустым"));
                return false;
            }
            return true;
        }
        
        public function checkOnEmptyTags()
        {
            $pattern = "/<p[^>]*>[\s|&nbsp;]*<\/p>/"; 
            $this->body = preg_replace($pattern, '', $this->body); 
            $this->preview = preg_replace($pattern, '', $this->preview); 
            return true;
        }

        /**
         *
         * Get instances by types
         */

        public function getInstanceTypeController($type)
        {
            $array = array("1"=>"categories", "2"=>"records", "3"=>"pages", "4"=>"categories", "5"=>"links");
            return $array[$type];
        }
               
        public function instanceTypes()
        {
            return
                array("1"=>t("Категория"),
                    "2"=>t("Запись"),
                    "3"=>t("Страница"),
                    "4"=>t("Категория ссылок"),
                    "5"=>t("Ссылка"));
        }

        public function instanceStates()
        {
            return
                array("1"=>t("Черновик"),
                    "2"=>t("Опубликован"),
                    "3"=>t("Удален"));
        }

        public function getLink($admin = false)
        {
            $link = "";
            if ($this->state==2) {
                $link = !empty($this->ref) ? $this->ref : "/".Yii::app()->language."/view/".$this->id;
            }

            if ($admin AND $this->canEdit())
            {
                $link = "/admin/".Yii::app()->language."/".$this->getInstanceTypeController($this->type)."/add/iid/".$this->id;
            }

            return $link;
        }

        public function canDelete()
        {
            
            if (Yii::app()->user->role == "admin")
            {
                return true;
            }
            return false;
        }
        public function canEdit()
        {
            if (Yii::app()->user->role == "admin")
            {
                return true;
            }
            return false;
        }

        public function afterFind()
        {
            $states = $this->instanceStates();
            $types = $this->instanceTypes();

            $this->stateCaption = $states[$this->state];
            $this->typeCaption = $types[$this->type];

            $relations = InstanceRelations::model()->findAll("r_id = :r_id",array(":r_id"=>$this->id));

            if ($relations)
            {
                foreach ($relations as $relation)
                {
                    $this->parentCategories[] = $relation->p_id;
                    $this->parentCategoriesCaptions[] = Instances::model()->resetScope()->findByPk($relation->p_id)->caption;
                }
            }

            parent::afterFind();

        }

        public function afterSave()
        {

            if (!$this->duplicate)
            {
                // Deleting old relations
                InstanceRelations::model()->deleteAll("r_id = :r_id",array(":r_id"=>$this->id));
                if (!empty($this->parentCategories))
                {

                    foreach ($this->parentCategories as $category)
                    {
                        $relation = new InstanceRelations();
                        $relation->refreshMetaData();
                        $relation->r_id = $this->id;
                        $relation->p_id = $category;
                        $relation->save();
                    }

                }
            }

            parent::afterSave();
        }

        public function beforeSave()
        {

            if ($this->isNewRecord) {
                $this->owner_id = Yii::app()->user->id;

            
                if (empty($this->label))
                {
                    $this->label = time();
                }
            } else {
                if (empty($this->label))
                {
                    $this->label = time();
                }
            }

            return parent::beforeSave();
        }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                    'parents' => array(self::HAS_MANY, 'InstanceRelations', 'r_id'),
                    'menu' => array(self::BELONGS_TO, 'Menu', array("id"=>"instance_id")),
                    'childs' => array(self::HAS_MANY, "InstanceRelations", "p_id"),
                    'cCount' => array(self::STAT, "Comments", "instance_id")
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'caption' => t('Заголовок'),
			'preview' => t('Превью'),
			'body' => t('Текст'),
			'type' => t('Тип'),
			'state' => t('Состояние'),
			'ts' => t('Время создания'),
			'is_c' => t('Пользователи могут оставлять коментарии'),
                        'ref' => t('Ссылка на страницу'),
                        "owner_id" => t("Автор"),
                        "parentCategories"=>t("Категории"),
                        "search_string"=>t("Поиск"),
                        "label"=>t("Уникальная метка(необязательно)"),
                        "target"=>t("Цель"),
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
		$criteria->compare('caption',$this->caption,true, 'OR');
		$criteria->compare('preview',$this->preview,true, 'OR');
		$criteria->compare('body',$this->body,true, 'OR');

                return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        "pagination"=>array(
                            "pageSize"=>100
                        )
		));
	}
}