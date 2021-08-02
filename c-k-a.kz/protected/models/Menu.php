<?php

/**
 * This is the model class for table "menu".
 *
 * The followings are the available columns in table 'menu':
 * @property integer $id
 * @property string $group
 * @property integer $position
 * @property integer $parent
 * @property integer $instance_id
 * @property integer $ts
 *
 * The followings are the available model relations:
 * @property InstancesRu $instance
 */
class Menu extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Menu the static model class
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
		return 'menu';
	}

        public function defaultScope()
        {
            return array(
                "order"=>"position",
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
			array('group_id, instance_id', 'required'),
			array('position, parent, instance_id, ts, group_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, group_id, position, parent, instance_id, ts', 'safe', 'on'=>'search'),
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
			'instance' => array(self::BELONGS_TO, 'Instances', 'instance_id'),
                        'group' => array(self::BELONGS_TO, 'MenuGroups', 'group_id'),
                        "childs" => array(self::HAS_MANY, 'Menu', "parent")
		);
	}

        public function scopes()
        {
            return array(
                "top"=>array(
                    "condition" => "parent IS NULL"
                )
            );
        }

        /**
         * SCOPES WITH PARAMS
         */
        public function byGroup($group)
        {
            $this->getDbCriteria()->mergeWith(array(
                'condition'=>"group_id = ".intval($group),
            ));
            return $this;
        }

        public function hasInstance($group_id, $instance_id)
        {

            $Instance = Menu::model()->byGroup($group_id)->find("instance_id = :iid", array(":iid"=>$instance_id));
            if ($Instance)
            {
                return true;
            }
            return false;
        }

        public function afterFind()
        {
            if (!empty($this->label) AND isset($this->instance)) $this->instance->caption = $this->label;
        }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'position' => 'Position',
			'parent' => 'Parent',
			'instance_id' => 'Instance',
			'ts' => 'Ts',
                        'group_id' => t("Тип меню")
		);
	}

        public function saveMenuRecursive($menu,$group, $parent = null)
        {
            foreach ($menu as $k=>$v)
            {
                $m = new Menu();
                $m->refreshMetaData();
                $m->parent = $parent;
                $m->group_id = $group;
                $m->position = $k;
                $m->instance_id = $v['menu'];
                $m->save();

                if (isset($v['childs']))
                {
                    echo $m->id;
                    $this->saveMenuRecursive($v['childs'], $group, $m->id);
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
		$criteria->compare('group_id',$this->group_id,true);
		$criteria->compare('position',$this->position);
		$criteria->compare('parent',$this->parent);
		$criteria->compare('instance_id',$this->instance_id);
		$criteria->compare('ts',$this->ts);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}