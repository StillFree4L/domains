<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $login;
	public $password;
	public $rememberMe;
	public $username;
	//public $is_blocked;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('login, password', 'required', 'message' => t('Необходимо заполнить поле {attribute}')),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
			array('login', 'length', 'min'=>3, 'max'=>250,
				'tooShort' => t('Логин не может быть короче {min} симв.'),
			),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>t('Запомнить меня'),
			'login' => t('Логин'),
			'password' => t('Пароль'),
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->login,$this->password);
			if(!$this->_identity->authenticate()){
				$this->addError('password','Неверный логин или пароль');
			}			
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
            
		if($this->_identity===null){
                    $this->_identity=new UserIdentity($this->login,$this->password);
                    $this->_identity->authenticate();
                }
		if($this->_identity->errorCode === UserIdentity::ERROR_NONE){
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}else{
			return false;
		}
	}
}
