<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%employee}}".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $role
 * @property string $date
 * @property string $about
 * @property int $created_at
 * @property int $updated_at
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%employee}}';
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'role', 'date', 'about'], 'required'],
            [['date'], 'safe'],
            [['username', 'role'], 'string', 'max' => 200],
            [['email'], 'string', 'max' => 100],
            [['about'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Идентификатор'),
            'username' => Yii::t('app', 'Сотрудник'),
            'email' => Yii::t('app', 'E-mail'),
            'role' => Yii::t('app', 'Должность'),
            'date' => Yii::t('app', 'Дата рождения'),
            'about' => Yii::t('app', 'О себе'),
            'created_at' => Yii::t('app', 'Дата добавления'),
            'updated_at' => Yii::t('app', 'Дата изменения'),
        ];
    }
}
