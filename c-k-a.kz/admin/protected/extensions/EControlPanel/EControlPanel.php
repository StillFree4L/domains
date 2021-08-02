<?php

class EControlPanel extends CWidget
{

    public $baseButtons = array();
    public $buttons = array();
    public function run()
    {

        if (is_array($this->baseButtons) AND empty($this->baseButtons))
        $this->baseButtons = array(
              "add" => array(
                  "label"=>t("Добавить запись"),
                  "url"=>"/admin/".Yii::app()->language."/".Yii::app()->controller->id."/add"
                )
          );
        else $this->baseButtons = array();


        $this->buttons = array_merge($this->baseButtons, $this->buttons);

        $this->render("index");

    }

}

?>
