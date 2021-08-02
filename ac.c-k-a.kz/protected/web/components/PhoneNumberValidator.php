<?php

namespace app\components;

class PhoneNumberValidator extends \yii\validators\Validator
{

    public $allowEmpty = true;
    public function validateAttribute($model,$attribute)
    {
        $value=$model->$attribute;
        if($this->allowEmpty && $this->isEmpty($value))
            return;
        if(!$this->validateValue($value))
        {
            $message=$this->message!==null?$this->message:\Yii::t('yii','Телефонный номер должен начинатся с +7 или 7(8) и содержать только цифры');
            $model->addError($attribute,$message);
        }
    }

    public function validateValue($value)
    {
        if (substr($value, 0, 1) == "8" OR substr($value, 0, 1) == "7" OR substr($value, 0, 2) == "+7") {
            $s = substr($value, 1, strlen($value)-2);
            if (ctype_digit($s)) {
                return true;
            }

        }
        return false;
    }

}

?>