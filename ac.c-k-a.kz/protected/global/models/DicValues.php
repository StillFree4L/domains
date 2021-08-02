<?php

namespace glob\models;
use glob\components\ActiveRecord;

/**
 * This is the model class for table "dics".
 *
 * The followings are the available columns in table 'dics':
 * @property integer $id
 * @property integer $dic_id
 * @property string $name
 * @property string $category
 * @property integer $ts
 * @property string $info
 */
class DicValues extends ActiveRecord
{

    private static $_values = null;
    public static function fromDic($value, $inputs = null)
    {
        if (!self::$_values) {
            self::$_values = self::find()
                ->indexBy("id")
                ->all();
        }
        return isset(self::$_values[$value]) ? self::$_values[$value]->getNameWithInputs($inputs) : "Неизв.";
    }

    public static function findByDic($name) {

        if (!self::$_values) {
            self::$_values = self::find()
                ->joinWith(["dic"])
                ->indexBy("id")
                ->all();
        }

        return array_filter(self::$_values, function($v) use ($name) {
            return $v->dic->name == $name;
        });
    }

    public function getDic()
    {
        return $this->hasOne(Dics::className(), ["id"=>"dic_id"]);
    }

    public function getNameWithInputs($inputs) {
        $n = explode("{input}", $this->name);
        if (count($n) > 1) {
            $nn = "";
            for ($i=0; $i<count($n); $i++) {
                $nn .= $n[$i];
                if ($i != count($n)-1) {
                    if (is_array($inputs)) {
                        $nn .= $inputs[$i][0];
                    } else {
                        $nn .= $inputs;
                    }
                }
            }
            return $nn;
        }
        return $this->name;

    }

    public function getNameOnForm()
    {
        $n = explode("{input}", $this->name);
        if (count($n) > 1) {
            $nn = "";
            for ($i=0; $i<count($n); $i++) {
                $nn .= $n[$i];
                if ($i != count($n)-1) {
                    $nn .= "<input style='width:80px;' dname='".$this->dic->name."' dvalue='".$this->id."' i='".$i."' name='dic_inputs[".$this->dic->name."][".$this->id."][$i][]' class='form-control input-xs inline-block' />";
                }
            }
            return $nn;
        }
        return $this->name;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            [['dic_id','name'], 'required'],
            [['ts','dic_id'], 'number', 'integerOnly'=>true],
            [['name', 'category'], 'string', 'max'=>'255'],
            [['name', 'info'], 'safe']
        ];
    }

}
