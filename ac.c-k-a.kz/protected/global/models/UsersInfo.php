<?php

namespace glob\models;

use glob\components\ActiveRecord;
use Yii;

/**
 * This is the model class for table "users_info".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $last_name
 * @property string $first_name
 * @property string $middle_name
 * @property integer $grup
 * @property integer $prepay
 * @property string $sex
 * @property integer $nationality_id
 * @property string $reception_date
 * @property string $adress
 * @property integer $foreign_lang
 * @property integer $need_host
 * @property string $birthdate
 * @property integer $hasid
 * @property integer $hasdiploma
 * @property integer $hasmedid
 * @property integer $hasphotos
 * @property integer $haslist
 * @property integer $teach
 * @property integer $is_teacher
 * @property string $iin
 * @property string $identify_number
 * @property integer $hasdiploma_copy
 *
 * @property Chat[] $chats
 * @property InChat[] $inChats
 */
class UsersInfo extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'last_name', 'first_name'], 'required'],
            [['user_id', 'grup', 'prepay', 'nationality_id', 'foreign_lang', 'need_host', 'hasid', 'hasdiploma', 'hasmedid', 'hasphotos', 'haslist', 'teach', 'is_teacher', 'hasdiploma_copy'], 'integer'],
            [['reception_date', 'birthdate'], 'safe'],
            [['last_name', 'first_name', 'middle_name', 'sex'], 'string', 'max' => 50],
            [['adress'], 'string', 'max' => 1000],
            [['iin', 'identify_number'], 'string', 'max' => 12],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'last_name' => 'Last Name',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'grup' => 'Grup',
            'prepay' => 'Prepay',
            'sex' => 'Sex',
            'nationality_id' => 'Nationality ID',
            'reception_date' => 'Reception Date',
            'adress' => 'Adress',
            'foreign_lang' => 'Foreign Lang',
            'need_host' => 'Need Host',
            'birthdate' => 'Birthdate',
            'hasid' => 'Hasid',
            'hasdiploma' => 'Hasdiploma',
            'hasmedid' => 'Hasmedid',
            'hasphotos' => 'Hasphotos',
            'haslist' => 'Haslist',
            'teach' => 'Teach',
            'is_teacher' => 'Is Teacher',
            'iin' => 'Iin',
            'identify_number' => 'Identify Number',
            'hasdiploma_copy' => 'Hasdiploma Copy',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chat::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInChats()
    {
        return $this->hasMany(InChat::className(), ['user_id' => 'id']);
    }

    public function getFio()
    {
        return $this->last_name." ".$this->first_name.($this->middle_name ? " ".$this->middle_name : "");
    }

    public function getFio_with_group()
    {
        return $this->fio."(".$this->group->grup.")";
    }

    public function getGroup()
    {
        return $this->hasOne(Grup::className(), ["id"=>"grup"]);
    }

}
