<?php

namespace glob\models;

use glob\components\ActiveRecord;

/**
 * This is the model class for table "comments".
 *
 * The followings are the available columns in table 'comments':
 * @property integer $id
 * @property integer $user_id
 * @property integer $target_id
 * @property integer $target_type
 * @property string $comment
 * @property string $attachments
 * @property integer $ts
 * @property integer $parent_id
 * @property integer $state
 * @property integer $state_ts
 */
class Comments extends ActiveRecord
{
    public $count;
    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			[['user_id', 'target_id', 'target_type', 'comment', 'ts'], 'required'],
			[['user_id', 'target_id', 'target_type', 'ts', 'parent_id'], 'number', 'integerOnly'=>true],
			[['comment'], 'string', 'max'=>2000],
			[['attachments'], 'safe'],
		];
	}

	public function getUser()
    {
        return $this->hasOne(Users::className(), ["id"=>"user_id"]);
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'user_id' => 'User',
			'target_id' => 'Target',
			'target_type' => 'Target Type',
			'comment' => 'Comment',
			'attachments' => 'Attachments',
			'ts' => 'Ts',
			'parent_id' => 'Parent',
		];
	}

	public function getTarget()
    {
        if ($this->target_type == TargetTypes::platform) {
            return Platforms::findOne($this->target_id);
        }
        if ($this->target_type == TargetTypes::order) {
            return Orders::findOne($this->target_id);
        }
        if ($this->target_type == TargetTypes::chat) {
            return Chats::findOne($this->target_id);
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes) {
        if ($this->state == ActiveRecord::DELETED) {
            $childs = Comments::find()->andWhere(["parent_id"=>$this->id])->all();
            if ($childs) {
                foreach ($childs as $c) {
                    $c->state = ActiveRecord::DELETED;
                    $c->save();
                }
            }
        }

        $target = $this->getTarget();
        if ($target AND method_exists($this->getTarget(), "afterCommentInsert"))
        {
            $target->afterCommentInsert($this);
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    public function getAccess($attributes = [])
    {
        return true;
    }
    public function pollAccess($attributes)
    {
        return true;
    }
    public function getRequest($attributes = [])
    {

        $comments = Comments::find();

        $comments->signed();
        if (!$attributes['noInfo']) {
            $comments->joinWith([
                "user" => function($query) {
                    return $query;
                }
            ]);
        }

        $comments->byDateDesc();

        if (isset($attributes['before_ts'])) {
            $comments->andWhere("comments.ts <= :before_ts", [
                ":before_ts"=>$attributes['before_ts']
            ]);
        }

        $after_ts = null;
        if (isset($attributes['after_ts'])) {
            $after_ts = $attributes['after_ts'];
        } else {
            $after_ts = Comments::find()->select("comments.ts")->andWhere([
                "target_id"=>$attributes['target_id'],
                "target_type"=>$attributes['target_type']
            ])->orderBy("comments.ts DESC")->asArray()->one()['ts'] - 259200;
        }
        $comments->andWhere("comments.ts >= :after_ts", [
            ":after_ts"=>$after_ts
        ]);

        $comments->andWhere([
            "target_id"=>$attributes['target_id'],
            "target_type"=>$attributes['target_type']
        ]);

        $comments = $comments->all();

        if ($comments) {
            return ActiveRecord::arrayAttributes($comments, (!$attributes['noInfo']) ? ["user" => [
                "fields"=>["id", "fio", "roleCaption","logoPreview"],
                "relations"=>[]
            ]] : [], [], false);
        }
        return [];
    }

    public function pollRequest($attributes = [])
    {

        $comments = Comments::find();
        $comments->signed();
        $comments->joinWith([
            "user" => function($query) {
                return $query->joinWith(["myAd"]);
            }
        ]);
        $comments->byDateDesc();
        if (isset($attributes['last_ts'])) {
            $comments->andWhere("comments.ts > :ts", [
                ":ts"=>$attributes['last_ts']
            ]);
        }
        $comments->andWhere([
            "target_id"=>$attributes['target_id'],
            "target_type"=>$attributes['target_type']
        ]);

        $comments = $comments->all();
        if ($comments)
        {
            return [
                "data"=>ActiveRecord::arrayAttributes($comments, ["user" => [
                    "fields"=>["id", "fio", "roleCaption","logoPreview"],
                    "relations"=>[]
                ]], [], false),
                "updateValue"=>$comments[0]->ts
            ];
        }

        return [
            "data"=>[]
        ];
    }
    public function insertAccess($attributes = [])
    {
        if (!\Yii::$app->user->isGuest) return true;
        return false;
    }
    public function insertRequest($attributes = [])
    {

        $this->user_id = \Yii::$app->user->id;
        $this->ts = time();
        $this->attributes = $attributes;
        if ($this->save())
        {
            return ActiveRecord::arrayAttributes($this, ["user" => [
                "fields" => ["id", "fio", "roleCaption"],
                "relations"=>[]
            ]], [], false);
        } else {
            return false;
        }
    }
    public function deleteAccess($attributes = []) {

        $comment = $this;
        if (!$this->id AND $attributes['id']) {
            $comment = Comments::findOne($attributes['id']);
        }

        if ($comment->user_id === \Yii::$app->user->id) return true;
        return false;
    }
    public function deleteRequest($attributes = [])
    {
        $c = Comments::findOne($attributes['id']);
        if (!$c) {
            return false;
        }
        $c->state = ActiveRecord::DELETED;
        if ($c->save()) {
            return [
                "deleted"=>1
            ];
        }
        return false;
    }

}
