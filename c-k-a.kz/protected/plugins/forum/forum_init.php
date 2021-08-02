<?php

class forum_init
{
    public function init()
    {

        Yii::setPathOfAlias("forum", dirname(__FILE__));
        
        Yii::app()->controllerMap['forum']='forum.ForumController';
        Yii::import("forum.models.*");

        //Instances::model()->addEventHandler("question_answer_init","afterFind","afterFind");

    }
}

?>
