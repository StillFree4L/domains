<?php

class survey_menu extends CWidget
{
    var $model;
    public function run()
    {
        $this->model = Yii::app()->plugins->survey;
        $this->render("menu");
    }
}

?>
