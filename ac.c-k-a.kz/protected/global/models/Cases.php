<?php

namespace glob\models;

use glob\components\ActiveRecord;
use Yii;

/**
 * This is the model class for table "cases".
 *
 * @property integer $id
 * @property integer $subject_id
 * @property integer $teacher_id
 * @property integer $ts
 * @property string $info
 * @property integer $semestr
 * @property integer $type
 */
class Cases extends \glob\components\ActiveRecord
{

    const TYPE_CASE = 1;
    const TYPE_UMDK = 2;
    const TYPE_SCHEDULE = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cases';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_id', 'info'], 'required'],
            [['subject_id', 'teacher_id', 'ts'], 'integer'],
            [['info'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'subject_id' => Yii::t('main', 'Subject ID'),
            'teacher_id' => Yii::t('main', 'Teacher ID'),
            'ts' => Yii::t('main', 'Ts'),
            'info' => Yii::t('main', 'Info'),
        ];
    }

    public function insertAccess($attributes = []) {
        return \Yii::$app->user->can(Users::ROLE_TEACHER);
    }

    public function insertRequest($attributes = [])
    {

        $case = new Cases();
        $case->teacher_id = Yii::$app->user->id; //($attributes['teacher_id'] AND Yii::$app->user->can(Users::ROLE_ADMIN)) ? $attributes['teacher_id'] : \Yii::$app->user->identity->profile->id;
        //$case->subject_id = $attributes['subject_id'];
        $case->semestr = $attributes['s'];
        $case->type = $attributes['type'];
        $case->info = $attributes['info'];

        if ($case->save()) {

            return ActiveRecord::arrayAttributes($case, [], array_merge((new Cases())->attributes(), ["infoJson"]), true);

        }

        $this->addErrors($case->getErrors());
        return false;

    }

    public function deleteAccess($attributes = [])
    {
        return \Yii::$app->user->can(Users::ROLE_TEACHER);
    }

    public function deleteRequest($attributes = []) {
        $case = Cases::findOne($attributes['id']);

        if ($case->teacher_id == \Yii::$app->user->identity->profile->id OR Yii::$app->user->can(Users::ROLE_ADMIN))
        {
            if ($case->delete()) {
                return true;
            }
        }
        return false;

    }

}
