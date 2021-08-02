<?php

class WebUser extends CWebUser {

    private $_model = null;
    private $_keyPrefix;

    public function getRole() {
        if ($user = $this->getModel()) {
                // в таблице User есть поле role
                return trim($user->role);
        }
    }

    private function getModel() {        
        if (!$this->isGuest && $this->_model === null) {
                $this->_model = Users::model()->findByPk($this->id);
        }
        return $this->_model;
    }

    public function init()
    {
        parent::init();
    }

    public function getStateKeyPrefix()
    {
            if($this->_keyPrefix!==null)
                    return $this->_keyPrefix;
            else
                    return $this->_keyPrefix=md5('Yii.umc-krg.kz');
    }
    
}
