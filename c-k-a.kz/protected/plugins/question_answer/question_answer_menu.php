<?php

class question_answer_menu extends CWidget
{
    var $model;
    public function run()
    {
        Yii::setPathOfAlias("question_answer", dirname(__FILE__));
        Yii::import("question_answer.models.*");
        $this->model = Plugins::model()->byName("question_answer")->find();
        $this->render("menu");
    }
}

?>
