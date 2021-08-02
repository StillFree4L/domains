<?php

class EInstanceAdd extends CWidget
{

    var $template = "add"; // add, view
    var $baseSideFields = array("ts"); // preview, body, ref
    var $baseFields = array("caption"); // preview, body, ref
    var $baseFieldsOptions = array(
        "body"=>array(
            "ref"=>false
            )
        );
    var $sideFields = array(); // state, categories, commentable
    var $type = "1";
    var $model = null;
    public function run()
    {

        if ($this->model == null)
        {
            $this->render("error", array("error"=>t('Ошибка. Укажите модель')));
            return;
        }

        $this->sideFields = array_merge($this->baseSideFields, $this->sideFields);
        
        // Instance.js
        $assets = dirname(__FILE__).'/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets,false,-1,true);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/instance.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/instance.css', CClientScript::POS_HEAD);

        /*
        $path = Yii::getPathOfAlias("frontend.extensions.bootstrap.assets.js");
        $baseUrl = Yii::app()->assetManager->publish($path,false,-1,false);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/bootstrap-tab.js', CClientScript::POS_HEAD);
        */

        $this->render("add/".$this->template);
    }

}

?>