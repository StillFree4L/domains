<?php

namespace glob\models\forms;
use glob\components\Model;

/**
 * Model for login users.
 * @property string $email
 * @property string $password
 * @property bool $rememberMe
 * @property array $disabled - if some fields must me read only
 */
class LoginForm extends Model
{

	public $login;
	public $password;
	public $rememberMe;
	private $_identity;        
    public $disabled = [];

	public function rules()
	{
            return [
                    ['login, password', 'required'],
                    ['rememberMe', 'boolean'],
            ];
	}

    public function attributeLabels()
	{
        return [
                'rememberMe'=>\Yii::t('main', 'Запомнить'),
                'login'=>\Yii::t('main', 'Логин'),
                'password'=>\Yii::t('main', 'Пароль'),
        ];
	}

    /**
     * Users logs in
     * @return boolean - if user passed identity
     */
	public function login($bySystem = false)
	{

//            if($this->_identity===null)
//            {
//                $this->_identity=new UserIdentity($this->login,$this->password);
//                $this->_identity->bySystem = $bySystem;
//                $this->_identity->authenticate();
//            }
//
//            if ($this->_identity->errorCode === UserIdentity::ERROR_PASSWORD_INVALID)
//            {
//                $this->addError("password",\Yii::t("main","Неправильный логин или пароль"));
//            }
//
//            if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
//            {
//
//                $duration=$this->rememberMe ? 3600*24*90 : 3600*24*1; // 30 days
//                \\Yii::$app->user->login($this->_identity,$duration);
//                return true;
//
//            }
//
//            else
//
//                return false;

	}

}

