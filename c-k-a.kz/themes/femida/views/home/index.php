<?php


$cs = Yii::app()->getClientScript();
$cs->registerCSSFile(Yii::app()->theme->getBaseUrl().'/css/home.css');


?>

<div class="home_page">

    <div class="">
    <?php
        
        $home1 = Instances::model()->byLabel("main")->find();
        $this->widget("ext.CInstance.CInstance",array(
            "model"=>$home1,
            "blocks"=>array("body")
        ));

        $home2 = Instances::model()->byLabel("awards")->find();
        $this->widget("ext.CInstance.CInstance",array(
            "model"=>$home2,
            "blocks"=>array("link_caption","preview")
        ));

    ?>
    </div>    

</div>

