<?php

namespace glob\models;
use glob\components\ActiveRecord;
use yii\log\Target;

/**
 * This is the model class for table "cart_ordered".
 *
 * The followings are the available columns in table 'cart_ordered':
 * @property integer $id
 * @property integer $creator_id
 * @property string $name
 * @property integer $ts
 * @property integer $last_ts
 *
 *
 * @property ChatMembers $member
 */
class Chats extends ActiveRecord
{

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            [['ts', 'creator_id'], 'number', 'integerOnly'=>true],
        ];
    }

    public function getIsMember($user_id = null)
    {
        $m = $this->getMember($user_id);
        if ($m) {
            return true;
        }
        return false;
    }

    public function getMember($user_id = null)
    {
        if (!$user_id) $user_id = \Yii::$app->user->id;
        $m = array_merge(array_filter($this->members, function($m) use ($user_id) {
            return $m->user_id == $user_id;
        }));
        if ($m) {
            return $m[0];
        }
        return false;
    }

    public function beforeSave($insert)
    {

        if ($this->isNewRecord) {
            $this->creator_id = \Yii::$app->user->id;
        }

        return parent::beforeSave($insert);
    }

    public function afterCommentInsert($comment)
    {
        $this->last_ts = $comment->ts;
        $this->save();
    }

    public function getMembers()
    {
        return $this->hasMany(ChatMembers::className(), ["chat_id"=>"id"]);
    }

    public function getLastMessage()
    {
        return $this->hasOne(Comments::className(), ["target_id"=>"id"])->andWhere(["comments.target_type"=>TargetTypes::chat])->orderBy("comments.ts DESC");
    }

    public static function getChatByUser($user) {

        $user_id = $user;
        if (is_object($user)) {
            $user_id = $user->id;
        }

        $chats = Chats::find()->joinWith(["members" => function($query) {
            return $query->indexBy("user_id");
        }])
            ->andWhere(["in", "chat_members.user_id", [$user_id, \Yii::$app->user->id]])->all();

        foreach($chats as $ch) {
            if (count($ch->members) == 2 AND isset($ch->members[$user_id]) AND isset($ch->members[\Yii::$app->user->id])) {
                return $ch;
            }
        }

        $transaction = self::getDb()->beginTransaction();
        $ch = new Chats();
        $ch->name = "";

        if ($ch->save()) {
            $m1 = new ChatMembers();
            $m1->user_id = \Yii::$app->user->id;
            $m1->chat_id = $ch->id;

            $m2 = new ChatMembers();
            $m2->user_id = $user_id;
            $m2->chat_id = $ch->id;

            if ($m1->save() AND $m2->save()) {
                $transaction->commit();
                return $ch;
            }

        }

        $transaction->rollback();
        return false;


    }

    public function insertAccess($attributes)
    {
        return true;
    }

    public function insertRequest($attributes)
    {
        $chat = self::getChatByUser($attributes['user_id']);
        if ($chat) {
            return ActiveRecord::arrayAttributes($chat, [
                "members" => [
                    'relations'=> [
                        'user' => [
                            'fields' => [
                                "id","fio","roleCaption","logoPreview"
                            ]
                        ]
                    ]
                ]
            ], [], true);
        }
    }

    public function updateAccess($attributes)
    {
        return true;
    }

    public function updateRequest($attributes)
    {
        if ($attributes['type'] == "updateView") {
            $chat = Chats::find()->byPk($attributes['id'])->one();
            if ($chat->member) {
                $chat->member->visit_ts = time();
                $chat->member->save();
            }
        }
        return [];
    }

    public function pollAccess($attributes)
    {
        return true;
    }

    public function getAccess($attributes)
    {
        return true;
    }

    public function pollRequest($attributes = [])
    {
        if (isset($attributes['type'])) {
            $method = "_poll".ucfirst($attributes['type']);
            return call_user_func([$this, $method], $attributes);
        } else {
            return $this->_pollRequest($attributes);
        }
    }

    public function getRequest($attributes = [])
    {
        if (isset($attributes['type'])) {
            $method = "_get".ucfirst($attributes['type']);
            return call_user_func([$this, $method], $attributes);
        } else {
            return $this->_getRequest($attributes);
        }
    }

    protected function _getRequest($attributes)
    {
        $attributes['last_ts'] = 0;
        $a = $this->_pollRequest($attributes);
        if ($a['data']) {
            return $a['data'];
        }
        return [];
    }

    protected function _getNewMessages($attributes)
    {
        $data = $this->_pollNewMessages($attributes);
        if ($data) {
            return [
                "messages"=>$data['data'],
                "last_ts"=>$data['updateValue']
            ];
        }
        return [];
    }

    protected function _pollRequest($attributes) {

        $ids = array_keys(ChatMembers::find()->signed()->andWhere([
            "user_id"=>\Yii::$app->user->id
        ])->indexBy("chat_id")->asArray()->all());
        $chats = Chats::find()->andWhere(["in", "id", $ids])->with([
            "members" => function($query){
                return $query->indexBy("user_id");
            },
            "lastMessage"
        ])->orderBy("chats.last_ts DESC");

        if (isset($attributes['last_ts'])) {
            $chats->andWhere("chats.last_ts >= :ts", [
                ":ts"=>$attributes['last_ts']
            ]);
        }

        $chats = $chats->all();
        if ($chats)
        {
            return [
                "data"=>ActiveRecord::arrayAttributes($chats, [
                    "members" => [
                        'relations'=> [
                            'user' => [
                                'fields' => [
                                    "id","fio","roleCaption","logoPreview"
                                ]
                            ]
                        ]
                    ],
                    "lastMessage"
                ], [], false),
                "updateValue"=>$chats[0]->last_ts
            ];
        }
        return [
            "data"=>[]
        ];
    }

    protected function _pollNewMessages($attributes)
    {

        $count = Comments::find()
            ->select("comments.*")
            ->leftJoin("chat_members members", "comments.target_id = members.chat_id AND comments.target_type = :t", [
                ":t"=>TargetTypes::chat
            ])
            ->andWhere(["members.user_id" => \Yii::$app->user->id])
            ->andWhere("comments.user_id != ".\Yii::$app->user->id)
            ->andWhere("members.visit_ts < comments.ts");
        if (isset($attributes['last_ts'])) {
            $count->andWhere("comments.ts > :ts", [
                ":ts"=>$attributes['last_ts']
            ]);
        }

        $messages = $count->all();
        if ($messages)
        {
            return [
                "data"=>ActiveRecord::arrayAttributes($messages, [], [], true),
                "updateValue"=>time()
            ];
        }
        return [
            "data"=>[]
        ];
    }

}
