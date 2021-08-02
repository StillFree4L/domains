<?php

class virtualLibrary_menu extends CWidget
{
    var $model;
    public function run()
    {
        $this->model = Yii::app()->plugins->virtualLibrary;
        $this->render("menu");
    }
}

?>
