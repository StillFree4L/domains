<?php

$this->layout = "//layouts/index_1column";

$cs = Yii::app()->getClientScript();
$cs->registerCSSFile(Yii::app()->theme->getBaseUrl().'/css/home.css');


?>

<div id="left_content">
    <div id="left_menu">
        <?php $this->widget("ext.CMenu.CMenu", array(
                "group"=>"left_menu"
            ));
        ?>
    </div>
    <div class="clear"></div>
</div>

<div id="main_content" class="column2">

    <div class="home_page">

        <div class="home_page_1">
        <?php

            $home1 = Instances::model()->byLabel("home1")->find();
            $this->widget("ext.CInstance.CInstance",array(
                "model"=>$home1,
                "blocks"=>array("caption")
            ));

        ?>
        </div>

        <div class="home_page_2" style="margin-top:30px;">
        <?php
            $home2 = Instances::model()->byLabel("home2")->find();            
            $this->widget("ext.CInstance.CInstance",array(
                "model"=>$home2,
                "limit"=>2,
            ));
        ?>
        </div>

    </div>
<div class="clear"></div>
</div>

