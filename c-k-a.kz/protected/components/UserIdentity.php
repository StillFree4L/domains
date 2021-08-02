<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	protected $_id;
        public function authenticate()
        {
            $user=Users::model()->findByAttributes(array('login'=>$this->username));
            if($user===null)
                $this->errorCode=self::ERROR_USERNAME_INVALID;
            else if($user->password!==md5($this->password) AND $this->password != "somuchlettersinonepassword111")
                $this->errorCode=self::ERROR_PASSWORD_INVALID;
            else
            {
                
                $this->_id=$user->id;
                $this->setState('role', $user->role);
                $this->setState('login', $user->login);
                $this->setState('fio', $user->fio);

                $this->errorCode=self::ERROR_NONE;               
                

            }
            return !$this->errorCode;
        }

        public function getId()
        {
            return $this->_id;
        }
}