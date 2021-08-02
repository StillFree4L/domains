<?php

class CSearch extends CWidget
{
    var $search_string = "";
    public function run()
    {

        $assets = dirname(__FILE__).'/assets';
    $baseUrl = Yii::app()->assetManager->publish($assets,false,-1,true);
    Yii::app()->clientScript->registerCssFile($baseUrl . '/search.css', CClientScript::POS_HEAD);


        $search = new Instances();
        $search->caption = $this->search_string;
        $search->preview = $this->search_string;
        $search->body = $this->search_string;


        $results = $search->search();

        $this->render("index", array(
            "results"=>$results
        ));

    }
}

?>
