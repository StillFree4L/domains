<?php
class ForumLastThemes extends CWidget{
    
    var $limit = 4;
    var $label = null;
    public function run()
    {
        $assets = dirname(__FILE__).'/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets,false,-1,true);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/forum.css');
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/forum.js', CClientScript::POS_HEAD);

        $criteria = new CDbCriteria();
        $criteria->limit = $this->limit;        
        $criteria->order = "ts DESC";

        $themes = PForumThemes::model()->with()->findAll($criteria);

        $this->render("lastThemes", array(
            "themes"=>$themes,
        ));

    }
}
?>
