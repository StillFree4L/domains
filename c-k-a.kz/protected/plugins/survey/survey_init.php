<?php

class survey_init
{

    public function init()
    {

        Yii::setPathOfAlias("survey", dirname(__FILE__));
        Yii::import("survey.models.*");
        
        //Instances::model()->addEventHandler("question_answer_init","afterFind","afterFind");

    }

    static function afterFind($model = null)
    {
        
    }

}

?>
