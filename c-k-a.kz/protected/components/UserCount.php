<?php
class UserCount
{
    var $count = true;
    public function init()
    {
        if ($this->count) {
            $this->addRecord();
        }
        
    }

    private function addRecord()
    {
                
        $ltime = (isset(Yii::app()->request->cookies['last_time_refreshed']) 
                ? Yii::app()->request->cookies['last_time_refreshed']->value
                : 0);

        if (time()-900 >= $ltime) {
            $this->_addRecord();
        }
        
    }

    private function _addRecord()
    {
        
        $uc = new UserCountModel();

        if (!empty(Yii::app()->user->id))
        {
            $uc ->user_id = Yii::app()->user->id;
            
        } else {

            $session=new CHttpSession;
            $session->open();      
            $uc -> session_id = $session->getSessionID();

        }
        
        $uc -> url = Yii::app()->request->url;
        
        if ($uc->save())
        {
            Yii::app()->request->cookies['last_time_refreshed'] = new CHttpCookie("last_time_refreshed", time());            
        } else {
            
        }

    }
  
}
?>