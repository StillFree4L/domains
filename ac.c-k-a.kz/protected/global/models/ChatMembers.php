<?php

namespace glob\models;
use glob\components\ActiveRecord;

/**
 * This is the model class for table "cart_ordered".
 *
 * The followings are the available columns in table 'cart_ordered':
 * @property integer $id
 * @property integer $chat_id
 * @property string $user_id
 * @property integer $ts
 * @property integer $visit_ts
 * @property integer $state
 * @property integer $deleted_ts
 */
class ChatMembers extends ActiveRecord
{

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            [['ts', 'chat_id', 'user_id', 'visit_ts', "deleted_ts"], 'number', 'integerOnly'=>true],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(Users::className(), ["id" => "user_id"]);
    }

    public function beforeSave($insert)
    {
        $this->visit_ts = time();
        return parent::beforeSave($insert);
    }

}
