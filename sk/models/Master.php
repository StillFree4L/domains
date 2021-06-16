<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master".
 *
 * @property int $id
 * @property string $name
 * @property string $date
 * @property string $role
 */
class Master extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'role'], 'required'],
            [['name', 'role'], 'string', 'max' => 255],
            [['date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор',
            'name' => 'ФИО',
            'date' => 'Дата рождения',
            'role' => 'Должность',
        ];
    }
    public function getRepairs()
    {
        return $this->hasOne(Repairs::className(), ['name'=>'username']);
    }
}
