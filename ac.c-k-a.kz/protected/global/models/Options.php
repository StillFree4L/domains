<?php

namespace glob\models;

use glob\components\ActiveRecord;
use Yii;

/**
 * This is the model class for table "options".
 *
 * @property integer $id
 * @property string $name
 * @property string $value
 */
class Options extends \glob\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'options';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'value'], 'string', 'max' => 2000],
            [['name'], 'unique'],
        ];
    }

    private static $_values = null;
    public static function byName($name)
    {
        if (!self::$_values) {
            self::$_values = self::find()
                ->indexBy("name")
                ->all();
        }
        return isset(self::$_values[$name]) ? self::$_values[$name]->value : "";
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'name' => Yii::t('main', 'Name'),
            'value' => Yii::t('main', 'Value'),
        ];
    }

    public function insertAccess($attributes) {
        if (\Yii::$app->user->can(Users::ROLE_SUPER)) {
            return true;
        }
        return false;
    }

    public function updateAccess($attributes) {
        if (\Yii::$app->user->can(Users::ROLE_SUPER)) {
            return true;
        }
        return false;
    }

    public function insertRequest($attributes) {

        $option = new self();
        $option->name = $attributes['name'];
        $option->value = $attributes['value'];

        if ($option->save()) {
            return ActiveRecord::arrayAttributes($option, [], [], true);
        } else {
            $this->addErrors($option->getErrors());
        }

        return false;

    }

    public function updateRequest($attributes) {

        $option = Options::findOne($attributes['id']);
        $option->value = $attributes['value'];

        if ($option->save()) {
            return ActiveRecord::arrayAttributes($option, [], [], true);
        } else {
            $this->addErrors($option->getErrors());
        }

        return false;
    }

}
