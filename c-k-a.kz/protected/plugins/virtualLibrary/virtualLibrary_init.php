<?php

class virtualLibrary_init
{

    public function init()
    {

        Yii::setPathOfAlias("virtualLibrary", dirname(__FILE__));
        Yii::app()->controllerMap['virtualLibrary']='virtualLibrary.VirtualLibraryController';
        Yii::import("virtualLibrary.models.*");
        
        //Instances::model()->addEventHandler("survey_init","afterFind","afterFind");

    }

    static function afterFind($model = null)
    {
        
    }

}

?>
